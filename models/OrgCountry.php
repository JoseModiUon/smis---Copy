<?php

namespace app\models;

use Yii;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_country".
 *
 * @property string $country_code
 * @property string $country_name
 * @property string|null $continent
 * @property string|null $region
 * @property string $code2
 * @property string|null $nationality
 *
 * @property SmStudent[] $smStudents
 */
class OrgCountry extends \yii\db\ActiveRecord
{
    use UserTracking;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_code', 'country_name', 'code2'], 'required'],
            [['country_code', 'code2'], 'string', 'max' => 5],
            [['country_name', 'continent', 'region', 'nationality'], 'string', 'max' => 100],
            [['country_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'country_code' => 'Country Code',
            'country_name' => 'Country Name',
            'continent' => 'Continent',
            'region' => 'Region',
            'code2' => 'Code 2',
            'nationality' => 'Nationality',
        ];
    }

    /**
     * Gets query for [[SmStudents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmStudents()
    {
        return $this->hasMany(SmStudent::className(), ['country_code' => 'country_code']);
    }
    /**
     * Gets query for [[SmStudents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudents()
    {
        return $this->hasMany(Student::className(), ['country_code' => 'country_code']);
    }
}
