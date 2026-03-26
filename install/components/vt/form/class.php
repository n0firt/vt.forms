<?php

use Bitrix\Main\Loader;
use \Bitrix\Main\DI\ServiceLocator;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class VtForm extends CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->startResultCache()) {
            $this->initResult();

            if (empty($this->arResult)) {
                $this->abortResultCache();
                ShowError('Форма не найдена');

                return;
            }

            $this->includeComponentTemplate();
        }
    }

    private function initResult(): void
    {
        if (!Loader::includeModule('vt.forms')) {
            return;
        }

        $serviceLocator = ServiceLocator::getInstance();

        /* @var $formsRepository \Vt\Forms\Service\FormRepository */
        $formsRepository = $serviceLocator->get('vt.forms.repository');

        /* @var $form \Vt\Forms\Model\Form */
        $form = $formsRepository->get($this->arParams['FORM_ID']);
    }
}
