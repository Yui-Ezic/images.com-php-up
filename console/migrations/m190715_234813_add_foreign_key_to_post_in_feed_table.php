<?php

use yii\db\Migration;

/**
 * Class m190715_234813_add_foreign_key_to_post_in_feed_table
 */
class m190715_234813_add_foreign_key_to_post_in_feed_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey("fk-feed-post_id", "{{%feed}}", "post_id", "{{%post}}", "id", "CASCADE");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("fk-feed-post_id", "{{%feed}}");
    }
}
