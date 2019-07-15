<?php

use yii\db\Migration;

/**
 * Handles adding is_avaliable to table `{{%comment}}`.
 */
class m190715_120843_add_is_avaliable_column_to_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('comment', 'is_avaliable', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('comment', 'is_avaliable');
    }
}
