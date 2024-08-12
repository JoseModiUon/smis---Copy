<?php

namespace app\models;

use Yii;
use app\components\Basic;
use app\models\traits\ModelTrait;
use app\models\traits\UserTracking;

/**
 * This is the model class for table "org_kuccps_prog_map".
 *
 * @property int $prog_map_id
 * @property string $kuccps_prog_code
 * @property string $uon_prog_code
 */
class OrgKuccpsProgMap extends \yii\db\ActiveRecord
{
    use UserTracking;

    use ModelTrait;

    public $batchFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smis.org_kuccps_prog_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kuccps_prog_code', 'uon_prog_code'], 'required'],
            [['prog_map_id'], 'integer'],
            [['kuccps_prog_code', 'uon_prog_code'], 'string', 'max' => 20],
            [['prog_map_id'], 'unique'],
            [['batchFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prog_map_id' => 'Prog Map ID',
            'kuccps_prog_code' => 'Kuccps Prog Code',
            'uon_prog_code' => 'Uon Prog Code',
        ];
    }



    public function upload(array $data) 
    {
        $completed = true;
        foreach($data as $row) {
            $map = new self();
            $map->assign($row);
            $map->batchFile = $this->batchFile;
            if(!$map->save()) {
                dd($map->getErrors());
                break;
                $completed = !$completed;
            }
        }
        return $completed;
    }



}
