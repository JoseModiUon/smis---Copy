<?php

namespace app\models\portal_student;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_acad_session_status".
 *
 * @property int $acad_session_status_id
 * @property string $acad_session_status_name
 * @property string $session_status
 */
class OrgAcadSessionStatus extends \yii\db\ActiveRecord
{
    use UserTracking;

    public static function getDb()
    {
        return Yii::$app->get('db2');
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smisportal.org_acad_session_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['acad_session_status_name'], 'required'],
            [['acad_session_status_name', 'session_status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'acad_session_status_id' => 'Acad Session Status ID',
            'acad_session_status_name' => 'Acad Session Status Name',
            'session_status' => 'Session Status',
        ];
    }
}
