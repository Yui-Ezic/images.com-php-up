<?php

use yii\db\Migration;

/**
 * Class m190716_141328_alter_table_user_drop_index_unique_on_username
 */
class m190716_141328_alter_table_user_drop_index_unique_on_username extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('username', 'user');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createIndex('username', 'user', 'username', $unique=true);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190716_141328_alter_table_user_drop_index_unique_on_username cannot be reverted.\n";

        return false;
    }
    */
}
