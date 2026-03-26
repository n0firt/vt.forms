<?

namespace Vt\Forms\Model;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;

class FormResultValuesTable extends DataManager
{
    public static function getTableName()
    {
        return 'vt_forms_result_values';
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
            new IntegerField(
                'RESULT_ID',
                [
                    'required' => true,
                ]
            ),
            new Reference('RESULT', FormResultTable::class, ['=this.RESULT_ID' => 'ref.ID']),
            new StringField(
                'CODE',
                [
                    'required' => true,
                ]
            ),
            new StringField(
                'LABEL',
                [
                    'required' => true,
                ]
            ),
            new StringField(
                'VALUE',
                [
                    'required' => true,
                ]
            ),
        ];
    }
}
