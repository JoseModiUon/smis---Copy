<?php

namespace app\modules\courseRegistration\utilities;

use app\models\CrProgCurrTimetable;
use app\models\CrProgrammeCurrLectureTimetable;
use app\modules\courseRegistration\traits\CrProgCurrTimetableTrait;
use app\modules\courseRegistration\models\CrProgCurrTimetable as ModelsCrProgCurrTimetable;
use app\modules\courseRegistration\models\CrProgrammeCurrLectureTimetable as ModelsCrProgrammeCurrLectureTimetable;
use app\modules\courseRegistration\models\CrProgrammeCurrLectureTimetable as lecTimetable;
use app\modules\courseRegistration\models\CrProgCurrTimetable as portalCrProgCurrTimetable;

class TimetablePublisher
{
    use CrProgCurrTimetableTrait;
    public function __construct(private array $timetableIds = [])
    {
    }

    public function publish(): bool
    {

        foreach ($this->timetableIds as $id) {
            $model = CrProgCurrTimetable::findOne($id);
            if ($model) {
                $model->publish_status = 1;
                $ok = $model->save();
                if (!$ok) {
                    break;
                }
            }
        }
        return $ok;
    }

    public function updatePublishStatus(int $timetable_id)
    {

        $model = CrProgCurrTimetable::findOne($timetable_id);

        if ($model->publish_status == 1) {
            return $this->updateModPublisherStatusColumn($timetable_id);
        }
        return true;
    }

}
