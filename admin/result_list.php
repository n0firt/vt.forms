<?php

use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Grid\Panel\Actions;
use Bitrix\Main\Grid\Panel\Snippet\Onchange;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Vt\Forms\Model\FormResultTable;
use Vt\Forms\Model\FormResultValuesTable;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

CModule::IncludeModule("vt.forms");

$APPLICATION->SetTitle("Результаты форм");

if (!$USER->isAdmin()) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

/*********************************************************************************************/

$grid_options = new Bitrix\Main\Grid\Options('result_list');
$sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
$nav_params = $grid_options->GetNavParams();

$nav = new Bitrix\Main\UI\PageNavigation('result_list');
$nav->allowAllRecords(true)
    ->setPageSize($nav_params['nPageSize'])
    ->initFromUri();

$onchange = new Onchange();
$onchange->addAction(
    [
        'ACTION' => Actions::CALLBACK,
        'CONFIRM' => true,
        'CONFIRM_APPLY_BUTTON'  => 'Подтвердить',
        'DATA' => [
            ['JS' => 'Grid.removeSelected()']
        ]
    ]
);

/*********************************************************************************************/

Loader::IncludeModule('vt.forms');

$serviceLocator = ServiceLocator::getInstance();

/* @var $formsRepository \Vt\Forms\Service\FormRepository */
$formsRepository = $serviceLocator->get('vt.forms.repository');

$forms = $formsRepository->getAll();

$columns = [];

$baseFields = FormResultTable::getEntity()->getFields();

foreach ($baseFields as $fieldName => $fieldObj) {
    if (
        $fieldObj instanceof IntegerField ||
        $fieldObj instanceof StringField
    ) {
        $columns[] = [
            'id' => $fieldName,
            'name' => $fieldName,
            'sort' => $fieldName,
            'default' => true
        ];
    }
}

$fields = [];

foreach ($forms as $form) {
    $fields = array_merge($fields, $form->getFields());
}

$uniqueFields = [];

foreach ($fields as $field) {
    if (!isset($uniqueFields[$field->getCode()])) {
        $uniqueFields[$field->getCode()] = $field;
    }
}

foreach ($uniqueFields as $field) {
    $columns[] = [
        'id' => $field->getCode(),
        'name' => $field->getLabel(),
        'sort' => $field->getCode(),
        'default' => true
    ];
}

/*********************************************************************************************/

$list = [];

$collection = FormResultTable::getList([
    'select' => ['*'],
    'filter' => $ormFilter ?? [],
    'order'  => $sorting['sort'] ?? ['ID' => 'DESC'],
    'offset' => $nav->getOffset(),
    'limit'  => $nav->getLimit(),
])->fetchCollection();

if ($collection) {
    $collection->fill(['VALUES']);

    foreach ($collection as $resultObj) {

        $row = [
            'ID'         => $resultObj->getId(),
            'FORM_ID'    => $resultObj->getFormId(),
            'IP'         => $resultObj->getIp(),
            'USER_AGENT' => $resultObj->getUserAgent(),
        ];

        if ($resultObj->getValues()) {
            foreach ($resultObj->getValues() as $valueObj) {
                $row[$valueObj->getCode()] = $valueObj->getValue();
            }
        }

        $list[] = [
            'data' => $row
        ];
    }
}

/*********************************************************************************************/

$APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', [
    'GRID_ID' => 'result_list',
    'COLUMNS' => $columns,
    'ROWS' => $list,
    'SHOW_ROW_CHECKBOXES' => false,
    'NAV_OBJECT' => $nav,
    'AJAX_MODE' => 'Y',
    'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
    'PAGE_SIZES' => [
        ['NAME' => "5", 'VALUE' => '5'],
        ['NAME' => '10', 'VALUE' => '10'],
        ['NAME' => '20', 'VALUE' => '20'],
        ['NAME' => '50', 'VALUE' => '50'],
        ['NAME' => '100', 'VALUE' => '100']
    ],
    'AJAX_OPTION_JUMP'          => 'N',
    'SHOW_CHECK_ALL_CHECKBOXES' => false,
    'SHOW_ROW_ACTIONS_MENU'     => true,
    'SHOW_GRID_SETTINGS_MENU'   => true,
    'SHOW_NAVIGATION_PANEL'     => true,
    'SHOW_PAGINATION'           => true,
    'SHOW_SELECTED_COUNTER'     => false,
    'SHOW_TOTAL_COUNTER'        => true,
    'SHOW_PAGESIZE'             => true,
    'SHOW_ACTION_PANEL'         => true,
    'ACTION_PANEL'              => [],
    'ALLOW_COLUMNS_SORT'        => false,
    'ALLOW_COLUMNS_RESIZE'      => true,
    'ALLOW_HORIZONTAL_SCROLL'   => true,
    'ALLOW_SORT'                => false,
    'ALLOW_PIN_HEADER'          => true,
    'AJAX_OPTION_HISTORY'       => 'N'
]);

/*********************************************************************************************/

require($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/include/epilog_admin.php");
