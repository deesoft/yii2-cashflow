<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property integer $book_id
 * @property integer $user_id
 * @property integer $can_add
 * @property integer $can_update_own
 * @property integer $can_delete_own
 * @property integer $can_update
 * @property integer $can_delete
 */
class Member extends \common\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['book_id', 'user_id'], 'required'],
            [['book_id', 'user_id', 'can_add', 'can_update_own', 'can_delete_own', 'can_update', 'can_delete'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'book_id' => 'Book ID',
            'user_id' => 'User ID',
            'can_create' => 'Can Create',
            'can_update_own' => 'Can Update Own',
            'can_delete_own' => 'Can Delete Own',
            'can_update' => 'Can Update',
            'can_delete' => 'Can Delete',
        ];
    }
}
