<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_sponsor".
 *
 * @property string $sponsor_name Name Of the sponsor e.g Ministry of Foreign Affairs, Ministry of Defense Uganda
 * @property bool|null $status default to True (0,1) 
 * @property int $sponsor_id
 * @property string $country_code
 */
class OrgSponsor extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_sponsor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sponsor_name', 'country_code'], 'required'],
            [['status'], 'boolean'],
            [['sponsor_name'], 'string', 'max' => 100],
            [['country_code'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sponsor_name' => 'Sponsor Name',
            'status' => 'Status',
            'sponsor_id' => 'Sponsor ID',
            'country_code' => 'Country Code',
        ];
    }
}
