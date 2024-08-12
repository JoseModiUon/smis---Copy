<?php

namespace app\extended;

class BaseModel extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->get('db');
    }

}
