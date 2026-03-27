<?php

namespace Vt\Forms\Model;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\EnumField;
use Bitrix\Main\Type\DateTime;

class RestQueueTable extends DataManager
{
    public static function getTableName()
    {
        return 'vt_forms_rest_queue';
    }

    public static function getMap()
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),

            new TextField('PAYLOAD', [
                'required' => true,
            ]),

            new EnumField('STATUS', [
                'values' => ['NEW', 'SENT', 'ERROR'],
                'default_value' => 'NEW',
                'title' => 'Статус отправки'
            ]),

            new TextField('LAST_ERROR'),

            new IntegerField('ATTEMPTS', [
                'default_value' => 0
            ]),

            new DatetimeField('DATE_CREATE', [
                'default_value' => function () {
                    return new DateTime();
                }
            ]),

            new DatetimeField('DATE_SENT'),
        ];
    }
}
