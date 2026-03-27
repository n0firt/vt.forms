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

        $logger->debug('vt.forms OnAfterAddFormResult', [
            'formId' => $formId,
            'resultId' => $resultId
        ]);

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
}
