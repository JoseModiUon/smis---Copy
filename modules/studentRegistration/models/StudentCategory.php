<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use JetBrains\PhpStorm\ArrayShape;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smisportal.sm_student_category".
 *
 * @property int $std_category_id
 * @property string $std_category_name
 */
class StudentCategory extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.sm_student_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['std_category_name'], 'required'],
            [['std_category_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[ArrayShape(['std_category_id' => "string", 'std_category_name' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'std_category_id' => 'Std Category ID',
            'std_category_name' => 'Std Category Name',
        ];
    }
}
