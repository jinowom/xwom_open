<?php

namespace backend\modules\xedit\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%xed_paper}}".
 *
 * @property int $id 报纸id
 * @property string $paper_name 报纸名称
 * @property string $unit_id 报刊所属单位ID
 * @property int $status 是否启用 0 删除  10 正常  11 禁用
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $operator 操作员
 */
class XedPaper extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xed_paper}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unit_id', 'status', 'created_at', 'updated_at', 'operator'], 'integer'],
            [['paper_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '报纸id',
            'paper_name' => '报纸名称',
            'unit_id' => '报刊所属单位ID',
            'status' => '是否启用 0 删除  10 正常  11 禁用',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'operator' => '操作员',
        ];
    }

    public static function getPapers(){
        $papers = self::findAllByWhere(['status' => 10],['paper_name','id']);
        return ArrayHelper::map($papers,'id','paper_name');
    }
}
