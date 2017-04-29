<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "book".
 *
 * @property integer $id
 * @property string $type
 * @property string $name
 * @property integer $photo_id
 * @property integer $team_id
 * @property string $description
 * @property integer $user_id
 * @property string $privacy
 *
 * @property User[] $users
 * @property Members[] $members
 * @property Book[] $books
 */
class Team extends \common\classes\ActiveRecord
{

    public $is_admin = false;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'book';
    }

    public static function find()
    {
        return parent::find()->alias('team');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'user_id',], 'required'],
            [['photo_id', 'team_id', 'user_id'], 'integer'],
            [['description'], 'string'],
            [['type', 'privacy'], 'string', 'max' => 8],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Name',
            'photo_id' => 'Photo ID',
            'team_id' => 'Team ID',
            'description' => 'Description',
            'user_id' => 'User ID',
            'privacy' => 'Privacy',
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
                ->viaTable('{{%member}}', ['book_id' => 'id']);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(Member::className(), ['book_id' => 'id']);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Book::className(), ['team_id' => 'id']);
    }
}
