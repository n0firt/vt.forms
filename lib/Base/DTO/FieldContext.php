<?php

namespace vt\forms\Base\DTO;

class FieldContext
{
    public function __construct(
        public string $code,
        public string $label,
        public bool $required = false,
        public bool $isTextArea = false
    ) {}
}
