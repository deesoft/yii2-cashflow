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
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User[] $members
 * @property Team $team
 */
class Book extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'user_id', 'created_at', 'updated_at'], 'required'],
            [['photo_id', 'team_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
                ->viaTable('{{%member}}', ['book_id' => 'id']);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }
}
