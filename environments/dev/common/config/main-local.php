<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2cashflow',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
            'serviceAccount' => '@app/config/firebase-credential.json'
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
        'frontendUrlManager' => [
            'hostInfo' => 'http://blog.master.dev/',
        ],
        'backendUrlManager' => [
            'hostInfo' => 'http://master.dev/',
        ],
        'restUrlManager' => [
            'hostInfo' => 'http://api.master.dev/',
        ],
    ],
];
