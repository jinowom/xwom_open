<?php

namespace backend\modules\xedit\models;

use common\models\BaseModel;
use Yii;

/**
 * This is the model class for table "{{%xed_news_file}}".
 *
 * @property string $id 主键，自增ID
 * @property string $new_id 稿件表 关联xed_news的id
 * @property string $file_name 文件上传时候的文件名
 * @property string $file_caption 新闻附件说明
 * @property string $file_type 文件类型：xml|pdf|jpg|png|doc|xls|ppt|zip|rar|
 * @property string $file_path 文件在服务器的路径
 * @property int $status 附件状态 1可用 0不可用
 * @property string $created_at 创建时间
 * @property int $created_by 创建用户ID
 * @property string $updated_at 更新时间
 * @property int $updated_by 更新用户ID
 */
class XedNewsFile extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xed_news_file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['new_id'], 'required'],
            [['new_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['file_name', 'file_caption'], 'string', 'max' => 255],
            [['file_type'], 'string', 'max' => 50],
            [['file_path'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键，自增ID',
            'new_id' => '稿件表 关联xed_news的id',
            'file_name' => '文件上传时候的文件名',
            'file_caption' => '新闻附件说明',
            'file_type' => '文件类型：xml|pdf|jpg|png|doc|xls|ppt|zip|rar|',
            'file_path' => '文件在服务器的路径',
            'status' => '附件状态 1可用 0不可用',
            'created_at' => '创建时间',
            'created_by' => '创建用户ID',
            'updated_at' => '更新时间',
            'updated_by' => '更新用户ID',
        ];
    }
}
