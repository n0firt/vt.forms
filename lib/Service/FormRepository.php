<?php

use Vt\Forms\Base\Form;
use Vt\Forms\Exception\FormNotFoundException;

class FormRepository
{
    private array $forms = [];

    public function __construct(Form ...$forms)
    {
        foreach ($forms as $form) {
            $this->add($form);
        }
    }

    public function add(Form $form): void
    {
        $this->forms[$form->getId()] = $form;
    }

    public function has(string $formId): bool
    {
        return isset($this->forms[$formId]);
    }

    public function get($formId): Form
    {
        if (!$this->has($formId)) {
            throw new FormNotFoundException("Форма '{$formId}' не найдена.");
        }
        return $this->forms[$formId];
    }

    public function getAll(): array
    {
        return $this->forms;
    }
}
