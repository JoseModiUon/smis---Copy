<?php

namespace app\modules\examProcessing;

use Yii;

/**
 * exam-processing module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\examProcessing\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->setLayoutPath(Yii::$app->layoutPath);
        $this->layout = 'main-contained';
    }
}
