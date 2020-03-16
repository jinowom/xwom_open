<?php

namespace backend\modules\xedit\models;

use Yii;

/**
 * This is the model class for table "{{%xed_news_type}}".
 *
 * @property int $id 递增ID
 * @property string $type 种类定义
 * @property int $status 附件状态 1可用 0不可用
 */
class XedNewsType extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xed_news_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['status'], 'integer'],
            [['type'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '递增ID',
            'type' => '种类定义',
            'status' => '附件状态 1可用 0不可用',
        ];
    }
}
