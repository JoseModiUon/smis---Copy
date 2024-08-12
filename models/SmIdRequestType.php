<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "sm_id_request_type".
 *
 * @property int $request_type_id
 * @property string|null $id_type_desc
 *
 * @property SmStudentIdRequest[] $smStudentIdRequests
 */
class SmIdRequestType extends \app\extended\BaseModel
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.sm_id_request_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_type_id'], 'required'],
            [['request_type_id'], 'default', 'value' => null],
            [['request_type_id'], 'integer'],
            [['id_type_desc'], 'string', 'max' => 30],
            [['request_type_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'request_type_id' => 'Request Type ID',
            'id_type_desc' => 'Id Type Desc',
        ];
    }

    /**
     * Gets query for [[SmStudentIdRequests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudentIdRequests()
    {
        return $this->hasMany(SmStudentIdRequest::class, ['request_type_id' => 'request_type_id']);
    }
}
