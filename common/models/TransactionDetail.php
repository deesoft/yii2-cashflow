<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transaction_detail".
 *
 * @property integer $id
 * @property integer $transaction_id
 * @property string $description
 * @property double $ammount
 */
class TransactionDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_id', 'ammount'], 'required'],
            [['transaction_id'], 'integer'],
            [['ammount'], 'number'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transaction_id' => 'Transaction ID',
            'description' => 'Description',
            'ammount' => 'Ammount',
        ];
    }
}
