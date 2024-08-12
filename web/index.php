<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/06/2024
 * @time: 11:59 AM
 */

require __DIR__ . '/../config/env_type_constants.php'; // environment specific i.e dev, prod, staging
require __DIR__ . '/../config/shared_constants.php'; // shared across all environments
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
