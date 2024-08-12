<?php

namespace app\modules\studentid\models;

class StudentCategory extends \app\models\SmStudentCategory
{



    /**
     * @return array
     */
    public static function studentCategory(): array
    {
        $data = self::find()
            ->orderBy('std_category_name')
            ->asArray()
            ->all();
        return \yii\helpers\ArrayHelper::map($data, 'std_category_id', 'std_category_name');
    }
}
