<?php

namespace app\notifications\notify;

use Yii;
use app\models\SmNameChange;
use tuyakhov\notifications\NotificationTrait;
use tuyakhov\notifications\NotificationInterface;

class NameChangeNotification implements NotificationInterface
{
    use NotificationTrait;

    public function __construct(private SmNameChange $nameChange)
    {
    }

    /**
     * Prepares notification for 'mail' channel
     */
    public function exportForMail()
    {
        return Yii::createObject([
           'class' => '\tuyakhov\notifications\messages\MailMessage',
           'view' => ['html' => 'name-change'],
           'viewData' => [
               'nameChange' => $this->nameChange,
           ]
        ]);
    }

}
