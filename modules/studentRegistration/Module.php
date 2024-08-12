<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

namespace app\modules\studentRegistration;

use Yii;
use yii\base\InvalidConfigException;
use yii\console\Application;

class Module extends \yii\base\Module
{
    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (Yii::$app instanceof Application) {
            Yii::configure(Yii::$app, require __DIR__ . '/config/console.php');

            $this->controllerNamespace = 'app\modules\studentRegistration\commands';
        }else{
            Yii::configure(Yii::$app, require __DIR__ . '/config/web.php');

            $this->controllerNamespace = 'app\modules\studentRegistration\controllers';

            $handler = $this->get('errorHandler');

            Yii::$app->set('errorHandler', $handler);

            $handler->register();
        }
    }
}
