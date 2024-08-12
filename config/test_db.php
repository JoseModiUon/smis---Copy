<?php

require __DIR__ . '/db_constants.php';

return [
    'smis' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'pgsql:host=' . SMIS_DB_SERVER . ';dbname=' . SMIS_DB_NAME,
        'username' => SMIS_DB_USER,
        'password' => SMIS_DB_PASS,
        'charset' => 'utf8',
    ],
    'smisportal' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'pgsql:host=' . SMIS_PORTAL_DB_SERVER . ';dbname=' . SMIS_PORTAL_DB_NAME,
        'username' => SMIS_PORTAL_DB_USER,
        'password' => SMIS_PORTAL_DB_PASS,
        'charset' => 'utf8',
    ]
];
