<?php

namespace app\modules\studentid\models;

use yii\helpers\ArrayHelper;

/**
 * {@inheritdoc}
 */
class StudentProgrammeCurriculum extends \app\models\SmStudentProgrammeCurriculum
{


    /**
     * @return array
     */
    public static function getCurriculum(): array
    {
        $data = self::find()
            ->joinWith('progCurriculum.prog')
//            ->where(['student_id' => \Yii::$app->user->id])
            ->orderBy('student_prog_curriculum_id')
            ->asArray()->all();

        return ArrayHelper::map($data, 'student_prog_curriculum_id', 'progCurriculum.prog.prog_full_name');
    }
}
