<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Web\Json;

$id = 'vt-form-' . $this->getComponent()->getComponentId();

?>

<form class="vt-form" id="<?= $id ?>?>">
    Форма "<?= $arResult['FORM_ID'] ?>"
</form>

<script>
    new VTForm(
        '<?= $id ?>',
        <?= Json::encode($arResult) ?>
    );
</script>