<?php

namespace Vt\Forms\Base\Fields;

use Vt\Forms\Base\DTO\FieldContext;

class PhoneField extends Field
{
    protected bool $isTextArea;

    public function __construct(FieldContext $context)
    {
        parent::__construct($context);
        $this->isTextArea = $context->isTextArea;
    }

    public function getType(): string
    {
        return 'text';
    }

    public function isTextArea(): bool
    {
        return $this->isTextArea;
    }
}
