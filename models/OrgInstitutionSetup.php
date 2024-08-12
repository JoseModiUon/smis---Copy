<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "smis.org_institution_setup".
 *
 * @property int $institution_setup_id
 * @property int $unit_id
 * @property string|null $logo_url
 * @property string|null $favicon_url
 * @property string|null $motto
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $website
 * @property string|null $postal_address
 */
class OrgInstitutionSetup extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_institution_setup';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unit_id'], 'required'],
            [['unit_id'], 'default', 'value' => null],
            [['unit_id'], 'integer'],
            [['motto'], 'string'],
            [['logo_url', 'favicon_url', 'postal_address'], 'string', 'max' => 400],
            [['email'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 100],
            [['website'], 'string', 'max' => 200],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrgUnit::class, 'targetAttribute' => ['unit_id' => 'unit_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'institution_setup_id' => 'Institution Setup ID',
            'unit_id' => 'Unit ID',
            'logo_url' => 'Logo Url',
            'favicon_url' => 'Favicon Url',
            'motto' => 'Motto',
            'email' => 'Email',
            'phone' => 'Phone',
            'website' => 'Website',
            'postal_address' => 'Postal Address',
        ];
    }
}
