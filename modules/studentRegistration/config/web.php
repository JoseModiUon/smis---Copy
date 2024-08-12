<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 */

use yii\symfonymailer\Mailer;

$params = require __DIR__ . '/../../../config/params.php';

$config = [
    'components' => [
        'errorHandler' => [
            'class' => 'yii\web\ErrorHandler',
            'errorAction' => 'student-registration/default/error',
        ],
        'mailer' => [
            /**
             * https://github.com/symfony/symfony-docs/issues/17115
             */
            'class' => Mailer::class,
            'viewPath' => '@app/modules/studentRegistration/mail',
            'useFileTransport' => false,
            'transport' => [
                'dsn' => 'gmail://smisadmin@uonbi.ac.ke:lziunystxuhwunjh@default'
            ]
        ],
        'log' => [
            'class' => 'yii\log\Target',
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'Africa/Nairobi',
            'dateFormat' => 'd-M-Y',
            'datetimeFormat' => 'd-M-Y H:i:s'
        ],
        'assetManager' => [
            /**
             * Yii loads assets from locally installed directories.
             * To improve on performance, we want to load these assets from CDNs where possible.
             */
            'class' => 'yii\web\AssetManager',
            'appendTimestamp' => true,
            'forceCopy' => YII_DEBUG,
//            'linkAssets' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js',
                    ]
                ],
                'yii\jui\JuiAsset' => [
                    'css' => [
                        'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css'
                    ],
                    'js' => [
                        'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'
                    ]
                ],
                'yii\bootstrap5\BootstrapAsset' => [
                    'css' => [
                        'https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css',
                    ]
                ],
                'yii\bootstrap5\BootstrapPluginAsset' => [
                    'js' => [
                        'https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js'
                    ],
                    'depends' => [
                        'yii\jui\JuiAsset',
                    ]
                ],

                /**
                 * Yii comes with some js assets under vendor/yiisoft/yii2/assets
                 * To improve on performance, we combine and minify these files
                 */
                'yii\web\YiiAsset' => [
                    'css' => [], 'js' => [], 'depends' => ['app\modules\studentRegistration\assets\AllYiiAssets']
                ],
                'yii\widgets\ActiveFormAsset' => [
                    'css' => [], 'js' => [], 'depends' => ['app\modules\studentRegistration\assets\AllYiiAssets']
                ],
                'yii\validators\ValidationAsset' => [
                    'css' => [], 'js' => [], 'depends' => ['app\modules\studentRegistration\assets\AllYiiAssets']
                ],
                'yii\grid\GridViewAsset' => [
                    'css' => [], 'js' => [], 'depends' => ['app\modules\studentRegistration\assets\AllYiiAssets']
                ],
                ' yii\captcha\CaptchaAsset' => [
                    'css' => [], 'js' => [], 'depends' => ['app\modules\studentRegistration\assets\AllYiiAssets']
                ]
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'gridview' => ['class' => 'kartik\grid\Module'],
    ],
];
return $config;
