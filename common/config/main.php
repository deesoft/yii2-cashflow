<?php
use yii\helpers\ArrayHelper;

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@bower' => dirname(dirname(__DIR__)) . '/vendor/bower-asset',
        '@npm' => dirname(dirname(__DIR__)) . '/vendor/npm-asset',
        '@runtime' => dirname(__DIR__) . '/runtime',
    ],
    'controllerMap' => [
        'image' => [
            'class' => 'mdm\upload\ImageController'
        ]
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\DbCache',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'normalizeUserAttributeMap' => [
                        'email' => ['emails', 0, 'value'],
                        'name' => 'displayName',
                        'profile' => 'url',
                        'avatar' => function ($attributes) {
                            return str_replace('?sz=50', '', ArrayHelper::getValue($attributes, 'image.url'));
                        },
                    ]
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'normalizeUserAttributeMap' => [
                        'avatar' => function ($attributes) {
                            return "https://graph.facebook.com/{$attributes['id']}/picture?width=1920";
                        },
                        'profile' => function ($attributes) {
                            return "https://www.facebook.com/{$attributes['id']}";
                        },
                    ],
                ],
                'github' => [
                    'class' => 'yii\authclient\clients\GitHub',
                    'normalizeUserAttributeMap' => [
                        'avatar' => 'avatar_url',
                        'nickname' => 'login',
                        'profile' => 'html_url'
                    ]
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'normalizeUserAttributeMap' => [
                        'avatar' => 'imageUrl',
                    ]
                ],
            ],
        ],
        'frontendUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '',
            'scriptUrl' => '/index.php',
            'rules' => require __DIR__ . '/../../frontend/config/url-rules.php',
            'enablePrettyUrl' => true,
        ],
        'backendUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '',
            'scriptUrl' => '/index.php',
            'rules' => require __DIR__ . '/../../backend/config/url-rules.php',
            'enablePrettyUrl' => true,
        ],
        'restUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '',
            'scriptUrl' => '/index.php',
            'rules' => require __DIR__ . '/../../rest/config/url-rules.php',
            'enablePrettyUrl' => true,
        ],
    ],
];
