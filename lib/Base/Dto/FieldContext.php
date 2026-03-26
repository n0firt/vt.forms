<?php

namespace Vt\Forms\Base\Dto;

final class FieldDto
{
    public function __construct(
        public readonly string $code,
        public readonly ?string $label = null,
        public readonly bool $required = false,
        public readonly bool $isTextArea = false
    ) {}
}
