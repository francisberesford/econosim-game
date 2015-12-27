<?php
return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'urlManager' => [
            'baseUrl' => 'http://econosim-game.local/',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => array(
                '<controller:\w+>/<id:\d+>'  => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ],
        'esAuthClients' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'registry' => [
                    'class' => 'yii\authclient\OAuth2',
                    'clientId' => 'econosim_game_client',
                    'clientSecret' => 'econosim_game_secret',
                    'tokenUrl' => 'http://econosim-registry.local/auth/token',
                    'authUrl' => 'http://econosim-registry.local/auth/index',
                    'apiBaseUrl' => 'http://econosim-registry.local/api',
                ],
            ],
        ],
    ]
];
