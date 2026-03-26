<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Vt\Forms\Model\FormResultTable;
use Vt\Forms\Model\FormResultValuesTable;

class vt_forms extends CModule
{
    public function __construct()
    {
        $this->MODULE_ID = "vt.forms";
        $this->MODULE_NAME = "ajax формы";
        $this->MODULE_DESCRIPTION = "Комплексный модуль для работы с ajax формами";

        $arModuleVersion = [];
        include __DIR__ . '/install.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        }
        if (is_array($arModuleVersion) && array_key_exists('VERSION_DATE', $arModuleVersion)) {
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
    }

    public function DoInstall(): void
    {
        RegisterModule($this->MODULE_ID);

        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();
    }

    public function DoUninstall(): void
    {
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();

        UnRegisterModule($this->MODULE_ID);
    }

    public function InstallDB(): bool
    {
        if (!Loader::includeModule($this->MODULE_ID)) {
            throw new \Exception('Модуль установлен некорректно');
        }

        FormResultTable::getEntity()->createDbTable();
        FormResultValuesTable::getEntity()->createDbTable();

        return true;
    }

    public function UnInstallDB(): bool
    {
        if (!Loader::includeModule($this->MODULE_ID)) {
            throw new \Exception('Модуль удалён некорректно');
        }

        $connection = Application::getInstance()->getConnection();
        $tableNames = [
            FormResultTable::getEntity()->getDBTableName(),
            FormResultValuesTable::getEntity()->getDBTableName(),
        ];

        foreach ($tableNames as $tableName) {
            if ($connection->isTableExists($tableName)) {
                $connection->dropTable($tableName);
            }
        }

        return true;
    }

    public function InstallEvents(): bool
    {
        return true;
    }

    public function UnInstallEvents(): bool
    {
        return true;
    }

    public function InstallFiles(): bool
    {
        return true;
    }

    public function UnInstallFiles(): bool
    {
        return true;
    }
}
