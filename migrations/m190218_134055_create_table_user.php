<?php

use yii\db\Migration;

/**
 * Class m190218_134055_create_table_user
 */
class m190218_134055_create_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("user", [
            'id' => $this->primaryKey(),
            'firstName' => $this->string(),
            'lastName' => $this->string(),
            'email' => $this->string(),
            'password_hash' => $this->string(),
            'token' => $this->string(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
