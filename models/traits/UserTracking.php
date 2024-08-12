<?php

namespace app\models\traits;

use Yii;

trait UserTracking
{

    public function beforeSave($insert)
    {
        $arr = explode('/', Yii::$app->request->getPathInfo());
        $action = array_pop($arr);
        $this->ip_address = Yii::$app->getRequest()->getUserIP();
        $this->userid = Yii::$app->user->identity->username;
        $this->last_update = new \yii\db\Expression('NOW()');
        $this->user_action = $action;
        return parent::beforeSave($insert);
    }
}
