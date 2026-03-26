<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arResult */

foreach ($arResult['FIELDS'] as &$field) {

    $requiredClass = ($field['REQUIRED'] === 'Y' || $field['REQUIRED'] === true) ? 'js-required' : '';

    $field['HTML'] = match ($field['TYPE']) {
        'textarea' => sprintf(
            '<div class="form__field %s">
                <label class="form__field-label" for="%s">%s</label>
                <textarea class="form__field-input" id="%2$s" name="%2$s"></textarea>
                <div class="form__error"></div>
            </div>',
            $requiredClass,
            $field['CODE'],
            $field['LABEL']
        ),

        'text', 'phone', 'email' => sprintf(
            '<div class="form__field %s">
                <label class="form__field-label" for="%s">%s</label>
                <input class="form__field-input" type="%s" id="%2$s" name="%2$s">
                <div class="form__error"></div>
            </div>',
            $requiredClass,
            $field['CODE'],
            $field['LABEL'],
            $field['TYPE']
        ),

        default => "",
    };
}
unset($field);
