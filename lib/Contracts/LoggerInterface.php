<?php

namespace Vt\Forms\Contracts;

interface LoggerInterface
{
    public function log(string $message, ?array $context = null): void;
}
