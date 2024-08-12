<?php

namespace app\modules\studentid\models\portal;

use app\models\portal\SmisportalSmIdRequestStatus;
use app\modules\studentid\models\IdRequestStatus;
use yii\helpers\ArrayHelper;

class SmisPortalIdRequestStatus extends SmisportalSmIdRequestStatus
{


    /**
     * @param string $status
     * @return array
     */
    public static function getStatusId(string $status = IdRequestStatus::PENDING)
    {
        $data = self::getRequestData($status);
        return ArrayHelper::getColumn($data, 'status_id');
    }

    /**
     * @param string $status
     * @return array|\yii\db\ActiveRecord[]
     */
    private static function getRequestData(string $status): array
    {
        return self::find()
            ->where(['status_name' => $status])
            ->orderBy('status_id')
            ->all();
    }

}
