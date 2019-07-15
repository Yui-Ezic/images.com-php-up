<?php

use yii\db\Migration;

/**
 * Class m190715_211045_alter_table_post_add_column_complaints
 */
class m190715_211045_alter_table_post_add_column_complaints extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%post}}', 'complaints', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%post}}', 'complaints');
    }
}
