<?php

namespace Vt\Forms;

use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Event;

class EventHandler
{
    public static function OnAfterAddFormResult(Event $event)
    {
        $arParameters = $event->getParameters();

        $formId = $arParameters['FORM_ID'];
        $resultId = $arParameters['RESULT_ID'];

        $serviceLocator = ServiceLocator::getInstance();
        $logger = $serviceLocator->get('vt.forms.logger');

        $logger->log(\Psr\Log\LogLevel::DEBUG, 'vt.forms OnAfterAddFormResult', [
            'formId' => $formId,
            'resultId' => $resultId
        ]);
    }

    public static function OnHitController(Event $event)
    {
        $arParameters = $event->getParameters();

        $formId = $arParameters['FORM_ID'] ?? '';
        $values = $arParameters['VALUES'] ?? [];

        $serviceLocator = ServiceLocator::getInstance();
        $logger = $serviceLocator->get('vt.forms.logger');

        $logger->log(\Psr\Log\LogLevel::DEBUG, 'vt.forms OnHitController', [
            'formId' => $formId,
            'values' => $values
        ]);
    }

    public static function OnBeforeRestSent(\Bitrix\Main\Event $event)
    {
        $params = $event->getParameters();
        $logger = \Bitrix\Main\DI\ServiceLocator::getInstance()->get('vt.forms.logger');

        $logger->info("REST: Попытка отправки", [
            'QUEUE_ID' => $params['QUEUE_ID'],
            'RESPONSE' => $params['RESPONSE']
        ]);
    }

    public static function OnRestSuccess(\Bitrix\Main\Event $event)
    {
        $params = $event->getParameters();
        $logger = \Bitrix\Main\DI\ServiceLocator::getInstance()->get('vt.forms.logger');

        $logger->info("REST: Успешно отправлено", [
            'QUEUE_ID' => $params['QUEUE_ID'],
            'RESPONSE' => $params['RESPONSE']
        ]);
    }

    public static function OnRestError(\Bitrix\Main\Event $event)
    {
        $params = $event->getParameters();
        $logger = \Bitrix\Main\DI\ServiceLocator::getInstance()->get('vt.forms.logger');

        $logger->error("REST: Ошибка отправки", [
            'QUEUE_ID' => $params['QUEUE_ID'],
            'HTTP_STATUS' => $params['STATUS'],
            'DETAILS' => $params['ERROR_TEXT'],
            'ATTEMPT' => $params['ATTEMPTS']
        ]);
    }
}
