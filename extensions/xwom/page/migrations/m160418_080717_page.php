<?php

use yii\db\Migration;

class m160418_080717_page extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%page}}', [
            'page_id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'html' => $this->text(),
            'sort' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%page}}');
    }
}
