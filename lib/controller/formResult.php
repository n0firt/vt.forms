<?php

namespace Vt\Forms\Controller;

use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Text\HtmlFilter;

class FormResult extends Controller
{
    public function getDefaultPreFilters()
    {
        return [
            new HttpMethod(
                [HttpMethod::METHOD_POST]
            ),
            new Csrf()
        ];
    }

    public function addAction(string $formId, array $values): ?array
    {
        $formId = HtmlFilter::encode($formId);

        foreach ($values as $key => $value) {
            $fields[$key] = HtmlFilter::encode($value);
        }

        $serviceLocator = ServiceLocator::getInstance();

        /* @var $formsRepository \Vt\Forms\Service\FormRepository */
        $formsRepository = $serviceLocator->get('vt.forms.repository');

        /* @var $form \Vt\Forms\Base\Form */
        $form = $formsRepository->get($formId);

        $fields = $form->getFields();

        foreach ($fields as $key => $field) {
            if ($field->isRequired() && !isset($values[$field->getCode()])) {
                $this->addError(new \Bitrix\Main\Error("Не заполнено обязательное поле \"{$field->getLabel()}\""));
            } else {
                $errors = $field->validate($values[$field->getCode()]);

                foreach ($errors as $error) {
                    $this->addError(new \Bitrix\Main\Error($error));
                }
            }
        }

        return null;
    }
}
