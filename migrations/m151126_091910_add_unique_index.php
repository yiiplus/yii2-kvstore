<?php

use yii\db\Migration;

class m151126_091910_add_unique_index extends Migration
{
    public function safeUp()
    {
        $this->createIndex('kvstore_unique_key_section', '{{%kvstore}}', ['section', 'key'], true);
    }

    public function safeDown()
    {
        $this->dropIndex('kvstore_unique_key_section', '{{%kvstore}}');
    }
}
