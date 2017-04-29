<?php

namespace rest\classes;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\VerbFilter;
use yii\filters\Cors;

/**
 * Description of Controller
 *
 * @property array $expands
 * @property array $exceptField
 * @property string $collectionEnvelope
 * @property array $properties
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Controller extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;
    protected $authOnly;
    protected $authExcept;
    protected $authOptional;
    protected $authMethods = [
        'yii\filters\auth\QueryParamAuth',
        'yii\filters\auth\HttpBearerAuth',
    ];
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbFilter' => [
                'class' => VerbFilter::className(),
                'actions' => $this->verbs(),
            ],
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'authMethods' => $this->authMethods,
                'only' => $this->authOnly,
                'except' => empty($this->authExcept) ? [] : $this->authExcept,
                'optional' => empty($this->authOptional) ? [] : $this->authOptional,
            ],
            'serializer' => [
                'class' => SerializeFilter::className(),
                'collectionEnvelope' => 'results',
            ],
            'corsFilter' => [
                'class' => Cors::className(),
            ],
        ];
    }

    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @return array the allowed HTTP verbs.
     */
    protected function verbs()
    {
        return [];
    }
}
