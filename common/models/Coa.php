<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wallet".
 *
 * @property integer $id
 * @property integer $book_id
 * @property integer $number
 * @property string $name
 * @property string $currency
 * @property bool $visibility
 * @property string $description
 */
class Coa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coa}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['book_id', 'number', 'name', 'currency'], 'required'],
            [['book_id', 'number'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
            [['currency'], 'string', 'max' => 8],
            [['visibility'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book ID',
            'account_number' => 'Account Number',
            'name' => 'Name',
            'currency' => 'Currency',
            'description' => 'Description',
        ];
    }
}
