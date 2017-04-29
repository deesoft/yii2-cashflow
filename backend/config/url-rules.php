<?php
return[
    'pages/<view>' => 'site/page',
    'pages/angularjs/<_x:.*>' => ['site/page', 'view' => 'angularjs'],
//    'pages/angularjs/(.*)' => ['site/page', 'view' => 'angularjs'],
    '' => 'site/index',
    '/<_x:(b|c|d).*>' => 'site/index'
];
