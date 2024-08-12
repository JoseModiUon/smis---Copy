<?php

namespace app\models\portal;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smisportal.sm_id_request_type".
 *
 * @property int $request_type_id
 * @property string|null $id_type_desc
 */
class SmisportalSmIdRequestType extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.sm_id_request_type';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_type_desc'], 'string', 'max' => 30],
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
}
