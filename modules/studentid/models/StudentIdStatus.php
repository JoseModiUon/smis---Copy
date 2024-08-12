<?php

namespace app\modules\studentid\models;

class StudentIdStatus extends \app\models\SmStudentIdStatus
{
    const ID_ACTIVE = 'ACTIVE';
    const ID_LOST = 'LOST';

    const ID_REPORTED_LOST = 'REPORTED_LOST';
    const ID_EXPIRED = 'EXPIRED';
    const ID_CLOSED = 'CLOSED';
    const ID_READY = 'READY';
    const ID_ISSUED = 'ISSUED';


}
