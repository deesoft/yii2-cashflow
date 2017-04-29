<?php

namespace yii\helpers;

use Yii;

/**
 * Description of Url
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Url extends BaseUrl
{
    public static $app;
    public static $apps = [
        'backend' => 'backendUrlManager',
        'frontend' => 'frontendUrlManager',
        'rest' => 'restUrlManager',
    ];

    /**
     * @inheritdoc
     */
    public static function toRoute($route, $scheme = false)
    {
        $route = (array) $route;
        $current = static::$app;
        foreach (static::$apps as $app => $value) {
            if (strpos($route[0], "@{$app}/") === 0) {
                $route[0] = substr($route[0], strlen($app) + 1);
                static::$app = $app;
                if($scheme === false){
                    $scheme = true;
                }
                break;
            }
        }
        try {
            $url = parent::toRoute($route, $scheme);
            static::$app = $current;
            return $url;
        } catch (\Exception $exc) {
            static::$app = $current;
            throw $exc;
        }
    }

    /**
     * @inheritdoc
     */
    protected static function getUrlManager()
    {
        if (static::$app !== null && isset(static::$apps[static::$app]) && ($manager = Yii::$app->get(static::$apps[static::$app], false)) !== null) {
            return $manager;
        }
        return parent::getUrlManager();
    }
}
