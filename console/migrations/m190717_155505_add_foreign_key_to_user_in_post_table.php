<?php

use yii\db\Migration;

/**
 * Class m190717_155505_add_foreign_key_to_user_in_post_table
 */
class m190717_155505_add_foreign_key_to_user_in_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey("fk-post-user_id", "{{%post}}", "user_id", "{{%user}}", "id", "CASCADE");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("fk-post-user_id", "{{%feed}}");
    }
}
