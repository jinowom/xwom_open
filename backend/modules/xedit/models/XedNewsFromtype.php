<?php

namespace backend\modules\xedit\models;

use common\models\BaseModel;
use Yii;

/**
 * This is the model class for table "{{%xed_news_fromtype}}".
 *
 * @property int $id 递增ID
 * @property string $type 来源定义
 * @property int $status 附件状态 1可用 0不可用
 */
class XedNewsFromtype extends BaseModel
{
    const FROM_TYPE_ADD =  '12';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xed_news_fromtype}}';
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
            'type' => '来源定义',
            'status' => '附件状态 1可用 0不可用',
        ];
    }
}
