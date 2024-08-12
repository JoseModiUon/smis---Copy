<?php

namespace app\modules\studentRecords;

use Yii;
use yii\console\Application;


/**
 * studentRecords module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here

        if (Yii::$app instanceof Application) {
            // Yii::configure(Yii::$app, require __DIR__ . '/config/console.php');

            $this->controllerNamespace = 'app\modules\studentRecords\commands';
        } else {
            // Yii::configure(Yii::$app, require __DIR__ . '/config/web.php');

            $this->controllerNamespace = 'app\modules\studentRecords\controllers';
        }
    }
}
