<?php

namespace Vt\Forms\Base;

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Vt\Forms\Base\Fields\Field;
use Vt\Forms\Exception\FormResultSavingException;
use Vt\Forms\Model\FormResultTable;
use Vt\Forms\Model\FormResultValuesTable;

class Form
{
    private string $id;
    private array $fields;

    public function __construct(string $id, Field ...$fields)
    {
        $this->id = $id;
        $this->fields = $fields;
    }

    public function addResult(array $values): bool
    {
        $connection = Application::getConnection();
        $request = Context::getCurrent()->getRequest();

        $connection->startTransaction();

        try {

            $result = FormResultTable::add([
                'FORM_ID' => $this->id,
                'IP' => $request->getRemoteAddress(),
                'USER_AGENT' => $request->getUserAgent(),
            ]);

            if ($result->isSuccess() === false) {
                throw new FormResultSavingException(implode(', ', $result->getErrorMessages()));
            }

            $id = $result->getId();

            foreach ($this->fields as $field) {
                $code = $field->getCode();
                $label = $field->getLabel();
                $value = $values[$code];

                if (empty($value) && $field->isRequired() === false) {
                    continue;
                }

                $resValue = FormResultValuesTable::add([
                    'RESULT_ID' => $id,
                    'CODE' => $code,
                    'LABEL' => $label,
                    'VALUE' => (string)$value,
                ]);

                if ($resValue->isSuccess() === false) {
                    throw new FormResultSavingException(implode(', ', $resValue->getErrorMessages()));
                }
            }

            $connection->commitTransaction();
        } catch (FormResultSavingException $exception) {
            $connection->rollbackTransaction();
            throw $exception;
        }

        $event = new \Bitrix\Main\Event("vt.forms", "OnAfterAddFormResult", [
            'FORM_ID' => $this->id,
            'RESULT_ID' => $id
        ]);
        $event->send();

        return true;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
