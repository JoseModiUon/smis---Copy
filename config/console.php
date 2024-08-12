<?php

/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/13/2023
 * @time: 11:35 AM
 */

use yii\symfonymailer\Mailer;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'student-id'
    ],
    'controllerNamespace' => 'app\commands',
    'timeZone' => 'Africa/Nairobi',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'db' => $db['smis'],
        'db2' => $db['smisportal'],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'Africa/Nairobi',
            'dateFormat' => 'd-M-Y',
            'datetimeFormat' => 'd-M-Y H:i:s'
        ],
    ],
    'params' => $params,
    'modules' => [
        'student-registration' => [
            'class' => 'app\modules\studentRegistration\Module',
        ],
        'student-id' => [
            'class' => 'app\modules\studentid\Module',
        ],
        'course-registration' => [
            'class' => 'app\modules\courseRegistration\Module',
        ],
        'student-records' => [
            'class' => 'app\modules\studentRecords\Module',
        ],
        'admissions' => [
            'class' => 'app\modules\admissions\Module',
        ],
        'fees-management' => [
            'class' => 'app\modules\feesManagement\Module',
        ],
    ],
];

return $config;
