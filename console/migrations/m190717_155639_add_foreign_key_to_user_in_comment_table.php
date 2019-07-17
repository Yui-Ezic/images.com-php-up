<?php

use yii\db\Migration;

/**
 * Class m190717_155639_add_foreign_key_to_user_in_comment_table
 */
class m190717_155639_add_foreign_key_to_user_in_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey("fk-comment-user_id", "{{%comment}}", "author_id", "{{%user}}", "id", "CASCADE");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("fk-comment-user_id", "{{%feed}}");
    }
}
