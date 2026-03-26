<?php

namespace vt\forms\Base\Fields;

class EmailField extends Field
{
    public function validate($value): array
    {
        $errors = parent::validate($value);

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Поле «{$this->label}» заполнено некорректно.";
        }

        return $errors;
    }

    public function getType(): string
    {
        return 'email';
    }
}
