<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/13/2023
 * @time: 11:35 AM
 */

use yii\symfonymailer\Mailer;

$params = require __DIR__ . '/../../../config/params.php';

$config = [
    'components' => [
//        'mailer' => [
//            'class' => Mailer::class,
//            'useFileTransport' => false,
//            'transport' => [
//                'dsn' => 'gmail://ndukenyadev@uonbi.ac.ke:jbycuzbmswtoahpg@default'
//            ]
//        ],
//        'log' => [
////            'class' => 'yii\log\Target',
////            'traceLevel' => YII_DEBUG ? 3 : 0,
//            'targets' => [
//                [
//                    'class' => 'yii\log\EmailTarget',
//                    'logVars' => [],
//                    'levels' => ['error', 'warning', 'info'],
//                    'except' => [
//                        'yii\db\Command:*',
//                    ],
//                    'message' => [
//                        'from' => ['ndukenyadev@uonbi.ac.ke'],
//                        'to' => ['ndukenyadev@uonbi.ac.ke'],
//                        'subject' => 'Student registration module logs on dev server',
//                    ],
//                ],
//            ],
//        ]
    ],
    'params' => $params
];
return $config;
