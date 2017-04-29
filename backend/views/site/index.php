<?php

use dee\angularjs\NgRoute;

/* @var $this yii\web\View */

$this->params['ngApp'] = 'bookApp';
?>
<?=
NgRoute::widget([
    'name' => 'bookApp',
    'preJsFile' => 'pre.js',
    'html5Mode' => true,
    'baseUrl' => '/',
    'depends' => ['ui.bootstrap'],
    'routes' => [
        '/' => [
            'templateFile' => 'templates/index.php',
            'controllerFile' => 'controllers/index.js',
            'controllerAs' => '$ctrl',
        ],
        '/b/:bookId' => [
            'templateFile'=>'templates/book.php',
            'controllerFile'=>'controllers/book.js',
            'controllerAs' => '$ctrl',
        ],
        '/c/:x'=>[
            'templateFile'=>'templates/test.php'
        ]
    ]
])
?>
