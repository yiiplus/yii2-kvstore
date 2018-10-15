<?php

class m140618_045255_create_kvstore extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable(
            '{{%yp_kvstore}}',
            [
                'id' => $this->primaryKey(),
                'group' => $this->string(255)->notNull(),
                'key' => $this->string(255)->notNull(),
                'value' => $this->text(),
                'description' => $this->string(255),
                'active' => $this->boolean(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $tableOptions
        );
    }

    public function down()
    {
        $this->dropTable('{{%yp_kvstore}}');
    }
}
