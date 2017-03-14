<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'phone' => $this->string(32)->unique(),
            // profile
            'fullname' => $this->string()->notNull(),
            'photo_id'=> $this->integer(),
            'avatar' => $this->string(),
            'birth_day' => $this->date(),
            'gender' => $this->string(10),
            'address' => $this->string(),
            'bio' => $this->text(),
            'raw_contants' => $this->text(),
            'raw_data' => $this->text(),
            // status
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%auth}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string(128),
            'source_id' => $this->string(128),

            'FOREIGN KEY ([[user_id]]) REFERENCES {{%user}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createTable('{{%client}}', [
            'id' => $this->bigPrimaryKey(),
            'notive_key' => $this->string(),
            'user_id' => $this->integer(),
            'raw_data' => $this->binary(),
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%client}}');
        $this->dropTable('{{%auth}}');
        $this->dropTable('{{%user}}');
    }
}
