<?php

namespace Vt\Forms\Base;

use Vt\Forms\Base\Fields\Field;
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
        $result = FormResultTable::add([
            'FORM_ID' => $this->id,
            'IP' => $_SERVER['REMOTE_ADDR'],
            'USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
        ]);

        if ($result->isSuccess() === false) {
            return false;
        }

        $id = $result->getId();

        foreach ($this->fields as $field) {
            $value = $values[$field->getCode()];

            if ($value === null) {
                continue;
            }

            FormResultValuesTable::add([
                'RESULT_ID' => $id,
                'CODE' => $field->getCode(),
                'LABEL' => $field->getLabel(),
                'VALUE' => $value,
            ]);
        }

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
