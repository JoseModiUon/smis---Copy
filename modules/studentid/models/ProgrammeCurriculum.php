<?php

namespace app\modules\studentid\models;

class ProgrammeCurriculum extends \app\models\OrgProgrammeCurriculum
{


    /**
     * @return array
     */
    public static function getProgrammes(): array
    {
        $data = self::find()
            ->joinWith('prog')
            ->asArray()
            ->all();

        return \yii\helpers\ArrayHelper::map($data, 'prog_curriculum_id', 'prog.prog_full_name');
    }

}
