<?php

namespace app\modules\studentRecords\utility;

use app\models\SmNameChange;
use app\modules\studentRecords\utility\models\SmNameChange as SmNameChangePortal;
use  app\modules\courseRegistration\traits\CrProgCurrTimetableTrait;

class Sync {
    use CrProgCurrTimetableTrait;
    public function syncNameChange()
    {
        $data = SmNameChangePortal::find()->asArray()->all();



        foreach ($data as $row) {
            $namechange = new SmNameChange();
            $this->assign($namechange, $row);
            $ok = $namechange->save();
        }
    }

    public function syncNameChangeRow($id) {
        $record = SmNameChange::find()->where(['name_change_id' => $id])->asArray()->one();
        $recordToUpdate = SmNameChangePortal::findOne($id);
        $this->assign($recordToUpdate, $record);
        $ok = $recordToUpdate->save();

    }

    public function syncNameChangeRowFromPortal($id) {
        $record = SmNameChangePortal::find()->where(['name_change_id' => $id])->asArray()->one();
        $recordToUpdate = SmNameChange::findOne($id);
        $this->assign($recordToUpdate, $record);
        $ok = $recordToUpdate->save();

    }
}
