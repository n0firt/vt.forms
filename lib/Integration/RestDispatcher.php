<?php

namespace Vt\Forms\Integration;

use Vt\Forms\Model\RestQueueTable;
use Bitrix\Main\Type\DateTime;

class RestDispatcher
{
    public static function OnAfterAddFormResult(\Bitrix\Main\Event $event)
    {
        $params = $event->getParameters();

        $payloadData = [
            'result_id' => $params['RESULT_ID'],
            'form_id'   => $params['FORM_ID'],
            'fields'    => $params['VALUES'],
            'sent_at'   => date('Y-m-d H:i:s')
        ];

        $result = RestQueueTable::add([
            'PAYLOAD' => json_encode($payloadData, JSON_UNESCAPED_UNICODE),
            'STATUS'  => 'NEW'
        ]);

        if ($result->isSuccess()) {
            \CAgent::AddAgent(
                "\\Vt\\Forms\\Integration\\RestDispatcher::sendResultAgent(" . $result->getId() . ");",
                "vt.forms",
                "N",    // Не периодический
                5,      // Интервал
                "",     // Дата первой проверки
                "Y",    // Активен
                date("d.m.Y H:i:s", time() + 5)
            );
        }
    }

    public static function sendResultAgent($queueId)
    {
        $queueItem = RestQueueTable::getById($queueId)->fetch();
        if (!$queueItem || $queueItem['STATUS'] === 'SENT') {
            return "";
        }

        if ((int)$queueItem['ATTEMPTS'] >= 3) {
            return "";
        }

        $beforeEvent = new \Bitrix\Main\Event("vt.forms", "OnBeforeRestSent", [
            'QUEUE_ID' => $queueId,
            'PAYLOAD'  => json_decode($queueItem['PAYLOAD'], true)
        ]);
        $beforeEvent->send();

        $httpClient = new \Bitrix\Main\Web\HttpClient(['socketTimeout' => 10]);
        $url = 'https://api.external-crm.com/v1/lead';

        $response = $httpClient->post($url, $queueItem['PAYLOAD']);
        $status = $httpClient->getStatus();

        $newAttempts = (int)$queueItem['ATTEMPTS'] + 1;

        if ($status === 200) {
            RestQueueTable::update($queueId, [
                'STATUS' => 'SENT',
                'DATE_SENT' => new DateTime(),
                'ATTEMPTS' => $queueItem['ATTEMPTS'] + 1
            ]);

            $successEvent = new \Bitrix\Main\Event("vt.forms", "OnRestSuccess", [
                'QUEUE_ID' => $queueId,
                'RESPONSE' => $response
            ]);
            $successEvent->send();

            return "";
        } else {
            $lastError = "HTTP {$status}: {$response}";

            RestQueueTable::update($queueId, [
                'STATUS' => 'ERROR',
                'LAST_ERROR' => $lastError,
                'ATTEMPTS' => $queueItem['ATTEMPTS'] + 1
            ]);

            $errorEvent = new \Bitrix\Main\Event("vt.forms", "OnRestError", [
                'QUEUE_ID'   => $queueId,
                'STATUS'     => $status,
                'ERROR_TEXT' => $lastError,
                'ATTEMPTS'   => $queueItem['ATTEMPTS'] + 1
            ]);
            $errorEvent->send();

            if ($newAttempts < 3) {
                return "\\Vt\\Forms\\Integration\\RestDispatcher::sendResultAgent(" . $queueId . ");";
            }

            return "";
        }
    }
}
