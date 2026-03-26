<?php

namespace Vt\Forms\Base;

use Vt\Forms\Base\Fields\Field;

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
        new \Exception('Not implemented');

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
