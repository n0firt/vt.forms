<?php

namespace vt\forms\Base\Fields;

class PhoneField extends Field
{
    public function validate($value): array
    {
        $errors = parent::validate($value);

        $cleanPhone = preg_replace('/[^\d]/', '', $value);

        if (!preg_match('/^[78]\d{10}$/', $cleanPhone)) {
            $errors[] = "Поле «{$this->label}» заполнено некорректно.";
        }

        return $errors;
    }

    public function getType(): string
    {
        return 'phone';
    }
}
