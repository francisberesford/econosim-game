<?php
return [
    'id' => 'econosim-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'name' => 'Econosim Website',
    'components' => [
        'db' => require(__DIR__ . '/db-local.php'),
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => [],
];
