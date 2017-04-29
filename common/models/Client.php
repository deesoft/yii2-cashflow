<?php

namespace common\models;

use Yii;
use yii\helpers\Json;
use common\helpers\Job;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property string $notive_key
 * @property integer $user_id
 * @property string $raw_data
 * @property User $user
 */
class Client extends \common\classes\ActiveRecord
{
    /**
     *
     * @var array
     */
    public $data = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%client}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['notive_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'notive_key' => 'Notification Key',
            'user_id' => 'User ID',
            'raw_data' => 'Raw Data',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->data = empty($this->raw_data) ? [] : Json::decode($this->raw_data);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->raw_data = Json::encode($this->data);
            return true;
        }
        return false;
    }

    /**
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     *
     * @param array $data
     */
    public function fcmSend($data)
    {
        if ($this->user_id) {
            $ids = static::find()->select(['notive_key'])
                ->where(['user_id' => $this->user_id])
                ->andWhere(['<>', 'notive_key', null])
                ->column();
        } elseif ($this->notive_key) {
            $ids = [$this->notive_key];
        } else {
            $ids = [];
        }
        if (count($ids)) {
            Job::fcmPush($ids, $data);
        }
    }

    public function get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    public function set($name, $value)
    {
        $this->data[$name] = $value;
        $this->save();
    }

    public function setValues(array $values)
    {
        foreach ($values as $key => $value) {
            $this->data[$key] = $value;
        }
        $this->save();
    }
}
