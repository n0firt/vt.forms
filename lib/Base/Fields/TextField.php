<?php

namespace Vt\Forms\Base\Fields;

use Vt\Forms\Base\Dto\FieldDto;

class TextField extends Field
{
    protected bool $isTextArea;

    public function __construct(FieldDto $fieldParams)
    {
        parent::__construct($fieldParams);
        $this->isTextArea = $fieldParams->isTextArea;
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
