<?php

use yii\db\Migration;

/**
 * Class m190716_115404_alter_tables_change_collate
 */
class m190716_115404_alter_tables_change_collate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = 'SELECT CONCAT("ALTER TABLE `", TABLE_NAME,"` CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;") AS mySQL'
                . ' FROM INFORMATION_SCHEMA.TABLES'
                . ' WHERE TABLE_SCHEMA="'.$this->getDsnAttribute('dbname', Yii::$app->getDb()->dsn).'"'
                . ' AND TABLE_TYPE="BASE TABLE"';
        $result = $this->db->createCommand($sql)->queryColumn();
        foreach ($result as $command)
        {
            echo 'Execute: ' . $command . PHP_EOL;
            $this->db->createCommand($command)->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190716_115404_alter_tables_change_collate cannot be reverted.\n";

        return true;
    }
    
    private function getDsnAttribute($name, $dsn)
    {
        if (preg_match('/' . $name . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }
}
