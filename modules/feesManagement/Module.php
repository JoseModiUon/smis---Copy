<?php

namespace app\modules\feesManagement;

use Yii;
use yii\console\Application;

/**
 * fees-management module definition class
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\feesManagement\controllers';

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (Yii::$app instanceof Application) {
            Yii::configure(Yii::$app, require Yii::getAlias('@app'). '/config/console.php');
            $this->controllerNamespace = 'app\modules\feesManagement\commands';
        }
    }
}
