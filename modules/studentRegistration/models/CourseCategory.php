<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 5/25/2023
 * @time: 12:52 PM
 */

namespace app\modules\studentRegistration\models;

use yii\db\ActiveRecord;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.cr_course_category".
 *
 * @property int $category_id
 * @property string $category_name
 */
class CourseCategory extends ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'smis.cr_course_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['category_id', 'category_name'], 'required'],
            [['category_id'], 'default', 'value' => null],
            [['category_id'], 'integer'],
            [['category_name'], 'string'],
            [['category_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'category_id' => 'Category ID',
            'category_name' => 'Category Name',
        ];
    }
}
