<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_id_request_status".
 *
 * @property int $status_id
 * @property string $status_name
 *
 * @property SmStudentIdRequest[] $smStudentIdRequests
 */
class SmIdRequestStatus extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_id_request_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'status_name'], 'required'],
            [['status_id'], 'default', 'value' => null],
            [['status_id'], 'integer'],
            [['status_name'], 'string', 'max' => 30],
            [['status_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'status_id' => 'Status ID',
            'status_name' => 'Status Name',
        ];
    }

    /**
     * Gets query for [[SmStudentIdRequests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudentIdRequests()
    {
        return $this->hasMany(SmStudentIdRequest::class, ['status_id' => 'status_id']);
    }
}
