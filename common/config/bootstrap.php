<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@rest', dirname(dirname(__DIR__)) . '/rest');
Yii::setAlias('@task', dirname(dirname(__DIR__)) . '/task');

Yii::$classMap['yii\helpers\Url'] = '@common/helpers/Url.php';
Yii::$container->set('yii\web\User', 'common\classes\User');
Yii::$container->set('yii\web\UrlManager', 'common\classes\UrlManager');
