<?php

use yii\db\Migration;

/**
 * Class m190218_134126_create_table_game
 */
class m190218_134126_create_table_game extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('game',[
            'id' => $this->primaryKey(),
            'left_gamer' => $this->integer()->notNull(),
            'right_gamer' => $this->integer()->notNull(),
            'attack_side' => $this->string()->notNull(),
        ]);
        $this->addForeignKey('fk_game_left_user', 'game', 'left_gamer', 'user', 'id', 'RESTRICT');
        $this->addForeignKey('fk_game_right_user', 'game', 'right_gamer', 'user', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('game');
    }
}
