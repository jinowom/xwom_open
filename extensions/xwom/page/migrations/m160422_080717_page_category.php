<?php

use yii\db\Migration;

class m160422_080717_page_category extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%page_category}}', [
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

            'url_key' => $this->string()->notNull(),
            'sort' => $this->integer()->notNull()->defaultValue(0),

            'INDEX (url_key)',
            'INDEX (root)',
            'INDEX (lft)',
            'INDEX (rgt)',
            'INDEX (lvl)',
            'INDEX (active)',
        ]);

        $this->addColumn('{{%page}}', 'url_key', $this->string()->notNull());
        $this->addColumn('{{%page}}', 'category_id', $this->integer()->notNull());
        $this->addColumn('{{%page}}', 'created_at', $this->integer()->notNull());
        $this->addColumn('{{%page}}', 'created_by', $this->integer()->notNull());
        $this->addColumn('{{%page}}', 'updated_at', $this->integer()->notNull());
        $this->addColumn('{{%page}}', 'updated_by', $this->integer()->notNull());
    }

    public function safeDown()
    {
        $this->dropTable('{{%page_category}}');

        $this->dropColumn('{{%page}}', 'url_key');
        $this->dropColumn('{{%page}}', 'category_id');
        $this->dropColumn('{{%page}}', 'created_at');
        $this->dropColumn('{{%page}}', 'created_by');
        $this->dropColumn('{{%page}}', 'updated_at');
        $this->dropColumn('{{%page}}', 'updated_by');
    }
}
