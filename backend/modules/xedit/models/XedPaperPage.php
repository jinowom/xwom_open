<?php

namespace backend\modules\xedit\models;

use Yii;

/**
 * This is the model class for table "{{%xed_paper_page}}".
 *
 * @property int $id 版面id
 * @property string $paper_id 报纸id
 * @property string $paper_issue 期次id 例：20191207
 * @property string $issue_name 期次名称
 * @property int $page 版面id 
 * @property string $page_name 版面名称
 * @property string $unit_id 报刊所属单位ID
 * @property int $status 是否启用 0 删除  10 正常  11 禁用
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $operator 操作员
 */
class XedPaperPage extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xed_paper_page}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paper_id', 'paper_issue', 'page', 'unit_id', 'status', 'created_at', 'updated_at', 'operator'], 'integer'],
            [['issue_name'], 'string', 'max' => 255],
            [['page_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '版面id',
            'paper_id' => '报纸id',
            'paper_issue' => '期次id 例：20191207',
            'issue_name' => '期次名称',
            'page' => '版面id ',
            'page_name' => '版面名称',
            'unit_id' => '报刊所属单位ID',
            'status' => '是否启用 0 删除  10 正常  11 禁用',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'operator' => '操作员',
        ];
    }
}
