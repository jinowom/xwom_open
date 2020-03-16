<?php

use yii\db\Migration;

class m160420_062040_helper_page_tree extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%helper_page}}', [
            'id' => $this->primaryKey(),
            'root' => $this->integer(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'lvl' => $this->smallInteger()->notNull(),
            'name' => $this->string(60)->notNull(),
            'icon' => $this->string(),
            'icon_type' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'active' => $this->smallInteger(1)->notNull()->defaultExpression('TRUE'),
            'selected' => $this->smallInteger(1)->notNull()->defaultExpression('FALSE'),
            'disabled' => $this->smallInteger(1)->notNull()->defaultExpression('FALSE'),
            'readonly' => $this->smallInteger(1)->notNull()->defaultExpression('FALSE'),
            'visible' => $this->smallInteger(1)->notNull()->defaultExpression('TRUE'),
            'collapsed' => $this->smallInteger(1)->notNull()->defaultExpression('FALSE'),
            'movable_u' => $this->smallInteger(1)->notNull()->defaultExpression('TRUE'),
            'movable_d' => $this->smallInteger(1)->notNull()->defaultExpression('TRUE'),
            'movable_l' => $this->smallInteger(1)->notNull()->defaultExpression('TRUE'),
            'movable_r' => $this->smallInteger(1)->notNull()->defaultExpression('TRUE'),
            'removable' => $this->smallInteger(1)->notNull()->defaultExpression('TRUE'),
            'removable_all' => $this->smallInteger(1)->notNull()->defaultExpression('FALSE'),

            'content' => $this->text(),
            'url_key' => $this->string()->notNull(),

            'INDEX (url_key)',
            'INDEX (root)',
            'INDEX (lft)',
            'INDEX (rgt)',
            'INDEX (lvl)',
            'INDEX (active)',
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%department}}');
    }
}
