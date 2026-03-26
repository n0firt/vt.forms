<?

namespace Vt\Forms\Model;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;
use Bitrix\Main\ORM\Fields\StringField;

class FormResultTable extends DataManager
{
    public static function getTableName()
    {
        return 'vt_forms_result';
    }

    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                ]
            ),
            new StringField(
                'FORM_ID',
                [
                    'required' => true,
                ]
            ),
            new StringField(
                'IP',
                [
                    'required' => true,
                ]
            ),
            new StringField(
                'USER_AGENT',
                [
                    'required' => true,
                ]
            ),
            new OneToMany(
                'VALUES',
                FormResultValuesTable::class,
                'RESULT_ID'
            )
        ];
    }
}
