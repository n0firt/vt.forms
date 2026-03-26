<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UI\Extension;

Extension::load([
    "masked_input"
]);

$id = $this->getComponent()->getId();

?>

<form class="vt-form" id="<?= $id ?>">
    <div class="form__title"><?= $arResult['TITLE'] ?></div>
    <div class="form__top-text"><?= $arResult['TOP_TEXT'] ?></div>
    <?php foreach ($arResult['FIELDS'] as $field) { ?>
        <?= $field['HTML'] ?>
    <? } ?>
    <input class="form__submit" type="submit" value="<?= $arResult['BUTTON_TEXT'] ?>">
    <div class="form__error"></div>
    <div class="form__success">
        <svg class="form__success-icon">
            <use xlink:href="<?= $this->GetFolder() ?>/sprite.svg#success"></use>
        </svg>
        <div class="form__success-text">Успешно отправлено</div>
        <span class="form__reset">Отправить ещё раз</span>
    </div>
</form>

<script>
    BX.ready(() => {
        new VTForm('<?= $id ?>', '<?= $arResult['FORM_ID'] ?>');
    });
</script>