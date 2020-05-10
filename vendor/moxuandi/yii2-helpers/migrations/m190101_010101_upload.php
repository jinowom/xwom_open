<?php
use yii\db\Migration;

/**
 * Class m190101_010101_upload
 *
 * 迁移指令:
 * ```
 * yii migrate --migrationPath=@vendor/moxuandi/yii2-helpers/migrations
 * ```
 *
 * @property string|null $tableOptions
 */
class m190101_010101_upload extends Migration
{
    const TABLE_NAME = '{{%upload}}';

    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),

            // 主要字段
            'real_name' => $this->string()->notNull()->defaultValue('')->comment('原始文件名'),
            'file_name' => $this->string()->notNull()->defaultValue('')->comment('新文件名'),
            'full_name' => $this->string()->notNull()->defaultValue('')->comment('完整的文件名(带路径)'),
            'process_name' => $this->string()->notNull()->defaultValue('')->comment('经过处理后的文件名(带路径)'),
            'file_size' => $this->integer()->notNull()->defaultValue(0)->comment('文件大小(单位:B)'),
            'file_type' => $this->string()->notNull()->defaultValue('')->comment('文件的 MIME 类型'),
            'file_ext' => $this->string()->notNull()->defaultValue('')->comment('文件扩展名'),
            'file_md5' => $this->string()->notNull()->defaultValue('')->comment('MD5'),
            'file_sha1' => $this->string()->notNull()->defaultValue('')->comment('SHA1'),

            'status' => $this->smallInteger()->notNull()->defaultValue(10)->comment('状态'),  // 是否启用(0:禁用, 10:启用)
            'is_delete' => $this->smallInteger()->notNull()->defaultValue(0)->comment('删除'),  // 是否删除(0:未删除, 1:已删除)
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
        ], $this->tableOptions . ' COMMENT="文件上传记录"');
    }

    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }


    /**
     * @var bool 是否使用`COLLATE utf8_unicode_ci ENGINE=InnoDB`或`utf8mb4_unicode_ci ENGINE=InnoDB`
     * @see http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
     */
    public $isInnoDB = true;

    /**
     * 设置表的排序方式
     * @return string|null
     */
    public function getTableOptions()
    {
        $tableOptions = null;
        if($this->isInnoDB && $this->db->driverName === 'mysql'){
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            switch($this->db->charset){
                case 'utf8mb4': $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB'; break;
                case 'utf8': $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'; break;
                default: break;
            }
        }
        return $tableOptions;
    }
}
