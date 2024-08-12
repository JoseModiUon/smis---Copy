<?php

use app\models\User;
use kartik\grid\Module;
use yii\caching\FileCache;
use yii\log\FileTarget;
use yii\symfonymailer\Mailer;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@photos' => '@app/uploads/avatars',
        '@uploadExistingStudentsPath' => '@app/uploads/admissions/existing/'
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'DoxD53x6jhMrWZDTyVIjIU2_UbkSyG-7',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],

        'cache' => [
            'class' => FileCache::class,
        ],
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => true,
        ],
        'authManager' => [
            'class' => 'app\components\DbManager'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db['smis'],
        'db2' => $db['smisportal'],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login' => 'site/login',
                'logout' => 'site/logout',
                '<module>/<controller>/<action:(update|delete|view|report-lost-id|print-single)>/<id:\d+>' => '<module>/<controller>/<action>',
            ],
        ],
        'formatter' => [
            'defaultTimeZone' => 'Africa/Nairobi',
            'dateFormat' => 'd-M-Y',
            'datetimeFormat' => 'd-M-Y H:i:s',
        ],
        'i18n' => [
            'translations' => [
                'yii2-ajaxcrud' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2ajaxcrud/ajaxcrud/messages',
                    'sourceLanguage' => 'en',
                ],
            ]
        ],
    ],
    'params' => $params,
    'modules' => [
        'student-records' => [
            'class' => 'app\modules\studentRecords\Module'
        ],
        'setup' => [
            'class' => 'app\modules\setup\Module',
        ],
        'courseRegistration' => [
            'class' => 'app\modules\courseRegistration\Module',
        ],
        'functionalSetup' => [
            'class' => 'app\modules\functionalSetup\Module',
        ],
        'student-registration' => [
            'class' => 'app\modules\studentRegistration\Module',
        ],
        'student-id' => [
            'class' => 'app\modules\studentid\Module',
        ],
        'exam-management' => [
            'class' => 'app\modules\examManagement\Module',
        ],
        'exam-processing' => [
            'class' => 'app\modules\examProcessing\Module',
        ],
        'fees-management' => [
            'class' => 'app\modules\feesManagement\Module',
        ],
        'admissions' => [
            'class' => 'app\modules\admissions\Module',
        ],
        // Other TP Modules
        'gridview' => [
            'class' => Module::class
        ],

        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ]
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        'allowedIPs' => ['*'],
    ];
}

return $config;
