<?php

use Bitrix\Main\Loader;
use Bitrix\Main\DI\ServiceLocator;
use Vt\Forms\Base\Fields\TextField;
use Bitrix\Main\Security\Random;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class VtForm extends CBitrixComponent
{
    private $componentId;
    public function executeComponent()
    {
        $this->componentId = 'vt_form_' . Random::getString(16, true);

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

    public function getId(): string
    {
        return $this->componentId;
    }

    private function initResult(): void
    {
        if (!Loader::includeModule('vt.forms')) {
            return;
        }

        $serviceLocator = ServiceLocator::getInstance();

        /* @var $formsRepository \Vt\Forms\Service\FormRepository */
        $formsRepository = $serviceLocator->get('vt.forms.repository');

        /* @var $form \Vt\Forms\Base\Form */
        $form = $formsRepository->get($this->arParams['FORM_ID']);

        $this->arResult = [
            'FORM_ID' => $form->getId(),
            'TITLE' => $this->arParams['TITLE'],
            'TOP_TEXT' => $this->arParams['TOP_TEXT'],
            'BUTTON_TEXT' => $this->arParams['BUTTON_TEXT'],
            'FIELDS' => []
        ];

        foreach ($form->getFields() as $field) {
            $params = [
                'CODE'  => $field->getCode(),
                'TYPE' => $field->getType(),
                'LABEL' => $field->getLabel(),
                'REQUIRED' => $field->isRequired(),
            ];

            if ($field instanceof TextField && $field->isTextArea() === true) {
                $params['TYPE'] = 'textarea';
            }

            $this->arResult['FIELDS'][] = $params;
        }
    }
}
