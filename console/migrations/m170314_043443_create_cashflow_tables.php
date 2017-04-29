<?php

use yii\db\Migration;

class m170314_043443_create_cashflow_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(8)->notNull()->defaultValue('book'), // book, team
            'name' => $this->string()->notNull(),
            'photo_id'=> $this->integer(),
            'team_id' => $this->integer(),
            'description' => $this->text(),
            'user_id' => $this->integer()->notNull(),
            'privacy' => $this->string(8)->notNull()->defaultValue('private'), // private, public
        ], $tableOptions);

        $this->createTable('{{%member}}', [
            'book_id' => $this->integer()->notNull(),
            'user_id'=> $this->integer()->notNull(),
            'is_admin' => $this->boolean()->defaultValue(false),
            'can_add' => $this->boolean()->defaultValue(false),
            'can_update_own' => $this->boolean()->defaultValue(false),
            'can_delete_own' => $this->boolean()->defaultValue(false),
            'can_update' => $this->boolean()->defaultValue(false),
            'can_delete' => $this->boolean()->defaultValue(false),

            'PRIMARY KEY ([[book_id]], [[user_id]])',
        ], $tableOptions);

        $this->createTable('{{%coa}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'number'=> $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'currency' => $this->string(8)->notNull(),
            'visibility' => $this->boolean()->defaultValue(true),
            'description' => $this->string(),
        ], $tableOptions);

        $this->createTable('{{%transaction_group}}', [
            'id' => $this->bigPrimaryKey(),
            'description' => $this->string(),
        ], $tableOptions);

        $this->createTable('{{%transaction}}', [
            'id' => $this->bigPrimaryKey(),
            'book_id' => $this->integer()->notNull(),
            'coa_id' => $this->integer()->notNull(),
            'group_id' => $this->bigInteger(),
            'user_id' => $this->integer()->notNull(),
            'date'=> $this->date()->notNull(),
            'description' => $this->string(),
            'ammount'=> $this->double()->notNull(),
            'detail' => $this->boolean()->defaultValue(false),
        ], $tableOptions);

        $this->createTable('{{%transaction_detail}}', [
            'id' => $this->bigPrimaryKey(),
            'transaction_id' => $this->bigInteger()->notNull(),
            'description' => $this->string(),
            'ammount'=> $this->double()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%transaction_detail}}');
        $this->dropTable('{{%transaction}}');
        $this->dropTable('{{%transaction_group}}');
        $this->dropTable('{{%coa}}');
        $this->dropTable('{{%member}}');
        $this->dropTable('{{%book}}');
    }
}
