<?php

namespace Vt\Forms\Base\Fields;

use Vt\Forms\Base\Dto\FieldDto;

abstract class Field
{
    protected string $code;
    protected string $label;
    protected bool $required;

    public function __construct(FieldDto $fieldParams)
    {
        $this->code = $fieldParams->code;
        $this->label = $fieldParams->label;
        $this->required = $fieldParams->required;
    }

    public function validate($value): array
    {
        if ($this->required === false) {
            return [];
        }

        $errors = [];

        if ($this->required && empty($value)) {
            $errors[] = "Поле «{$this->label}» обязательно для заполнения.";
        }

        if (is_string($value) && strlen($value) > 256) {
            $errors[] = "Поле «{$this->label}» не должно превышать 256 символов.";
        }

        return $errors;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    abstract public function getType(): string;
}
