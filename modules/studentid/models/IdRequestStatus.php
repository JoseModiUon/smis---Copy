<?php

namespace app\modules\studentid\models;

use app\models\SmIdRequestStatus;
use yii\helpers\ArrayHelper;


class IdRequestStatus extends SmIdRequestStatus
{
    const ISSUED = 'ISSUED';
    const READY = 'READY';
    const CLOSED = 'CLOSED';
    const PENDING = 'PENDING';
    const REJECTED = 'REJECTED';


    /**
     * @return array
     */
    public static function getRequestStatus(): array
    {
        $data = self::find()
            ->asArray()
            ->all();

        return ArrayHelper::map($data, 'status_id', 'status_name');
    }

    /**
     * @param string $status
     * @return array
     */
    public static function getRequestByStatus(string $status = self::PENDING): array
    {
        $data = self::getRequestData($status);

        return ArrayHelper::map($data, 'status_id', 'status_name');
    }

    /**
     * @param string $status
     * @return array
     */
    public static function getStatusId(string $status = self::PENDING)
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
