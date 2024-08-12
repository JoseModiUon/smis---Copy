<?php

namespace app\helpers;

use Yii;

class AuditTrail{
    public function main($model, $userAction)
    {
        //Getting the user's IP Address
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $user_ip = $_SERVER['REMOTE_ADDR'];
        } elseif (isset($_ENV['REMOTE_ADDR'])) {
            $user_ip = $_ENV['REMOTE_ADDR'];
        } elseif (getenv('REMOTE_ADDR')) {
            $user_ip = getenv('REMOTE_ADDR');
        } else {
            $user_ip = "Unknown";
        }

        //Checking if it's valid
        if (!filter_var($user_ip, FILTER_VALIDATE_IP)) {
            $user_ip = "Unknown";
        }

        $model->userid = Yii::$app->user->identity->username;
        $model->ip_address = $user_ip;
        $model->user_action = $userAction;
        date_default_timezone_set('Africa/Nairobi');
        $model->last_update = date("Y-m-d H:i:s", time());
    }
}