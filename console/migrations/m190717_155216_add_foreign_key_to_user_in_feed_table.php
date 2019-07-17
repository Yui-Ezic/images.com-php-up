<?php

use yii\db\Migration;

/**
 * Class m190717_155216_add_foreign_key_to_user_in_feed_table
 */
class m190717_155216_add_foreign_key_to_user_in_feed_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey("fk-feed-user_id", "{{%feed}}", "user_id", "{{%user}}", "id", "CASCADE");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("fk-feed-user_id", "{{%feed}}");
    }
}
