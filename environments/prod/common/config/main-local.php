<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
        'jwt' => [
            'class' => 'common\classes\Jwt',
            'secret' => '',
        ],
        'queue' => [
            'class' => 'dee\queue\queues\DbQueue',
            'module' => 'task',
        ],
        'firebase'=>[
            'class' => 'common\classes\Firebase',
            'serviceAccount' => '@common/config/firebase-credential.json'
        ],
        'authClientCollection' => [
            'clients' => [
                'google' => [
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'facebook' => [
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'github' => [
                    'clientId' => '',
                    'clientSecret' => '',
                ],
            ],
        ],
    ],
];
