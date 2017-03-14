<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property integer $id
 * @property integer $book_id
 * @property integer $coa_id
 * @property integer $group_id
 * @property integer $user_id
 * @property string $date
 * @property string $description
 * @property double $ammount
 * @property integer $detail
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['book_id', 'coa_id', 'user_id', 'date', 'ammount'], 'required'],
            [['book_id', 'coa_id', 'group_id', 'user_id', 'detail'], 'integer'],
            [['date'], 'safe'],
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
            'wallet_id' => 'Wallet ID',
            'group_id' => 'Group ID',
            'user_id' => 'User ID',
            'date' => 'Date',
            'description' => 'Description',
            'ammount' => 'Ammount',
            'detail' => 'Detail',
        ];
    }
}
