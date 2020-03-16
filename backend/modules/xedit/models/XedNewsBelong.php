<?php

namespace backend\modules\xedit\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%xed_news_belong}}".
 *
 * @property string $id 主键
 * @property string $news_id 投稿主键
 * @property string $out_id 所属id 第三方主键
 * @property string $unit_id 所属单位id 关联admin_unit表id
 * @property int $create_at 添加时间
 * @property int $update_at 修改时间
 * @property int $type 类型
 */
class XedNewsBelong extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xed_news_belong}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_id'], 'required'],
            [['news_id', 'out_id', 'unit_id', 'create_at', 'update_at', 'type'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'news_id' => '投稿主键',
            'out_id' => '所属id 第三方主键',
            'unit_id' => '所属单位id 关联admin_unit表id',
            'create_at' => '添加时间',
            'update_at' => '修改时间',
            'type' => '类型',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_at',// 自己根据数据库字段修改
                'updatedAtAttribute' => 'update_at',
//                'value'   => new Expression('NOW()'),
                'value'   => function(){return time();},
            ],
        ];
    }

    /**
     * @Function 添加关系
     * @Author Weihuaadmin@163.com
     */
    public function addBelong($data){
        if($this->load($data,'') && $this->validate()){
            return $this->save();
        }
        return false;
    }
}
