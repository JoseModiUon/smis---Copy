<?php

namespace app\modules\studentid\models;

class IdRequestType extends \app\models\SmIdRequestType
{


    /**
     * @return array
     */
    public static function idRequestType(): array
    {
        $data = self::find()->orderBy('request_type_id')->asArray()->all();
        return \yii\helpers\ArrayHelper::map($data, 'request_type_id', 'id_type_desc');
    }
}
