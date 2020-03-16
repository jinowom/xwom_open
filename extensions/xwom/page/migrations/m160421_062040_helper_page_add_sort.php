<?php

use yii\db\Migration;

class m160421_062040_helper_page_add_sort extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%helper_page}}', 'sort', $this->integer()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%helper_page}}', 'sort');
    }
}
