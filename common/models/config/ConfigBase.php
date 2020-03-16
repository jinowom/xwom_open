<?php
/**
 * This is the model class for table "ConfigBase";
 * @package common\models\config;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-05 16:42 */
namespace common\models\config;

use Yii;

/**
 * This is the model class for table "{{%config_base}}".
 *
 * @property int $id 主键
 * @property string $title 配置标题
 * @property string $name 配置标识
 * @property string $app_id 应用
 * @property string $type 配置类型
 * @property string $extra 配置值
 * @property string $description 配置说明
 * @property int $is_hide_des 是否隐藏说明
 * @property string $default_value 默认配置
 * @property int $sort 排序
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 * @property int $created_id 添加人员ID
 * @property int $updated_id 修改人员ID
 */
class ConfigBase extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%config_base}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_hide_des', 'sort', 'status', 'created_at', 'updated_at', 'created_id', 'updated_id'], 'integer'],
            [['title', 'name'], 'string', 'max' => 50],
            [['app_id'], 'string', 'max' => 20],
            [['type'], 'string', 'max' => 30],
            [['extra', 'description'], 'string', 'max' => 1000],
            [['default_value'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键'),
            'title' => Yii::t('app', '配置标题'),
            'name' => Yii::t('app', '配置标识'),
            'app_id' => Yii::t('app', '应用'),
            'type' => Yii::t('app', '配置类型'),
            'extra' => Yii::t('app', '配置值'),
            'description' => Yii::t('app', '配置说明'),
            'is_hide_des' => Yii::t('app', '是否隐藏说明'),
            'default_value' => Yii::t('app', '默认配置'),
            'sort' => Yii::t('app', '排序'),
            'status' => Yii::t('app', '状态[-1:删除;0:禁用;1启用]'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '修改时间'),
            'created_id' => Yii::t('app', '添加人员ID'),
            'updated_id' => Yii::t('app', '修改人员ID'),
        ];
    }
    
    /**
    *验证之前的处理.如果不需要，调试后请删除
    */
    /*
    public function beforeValidate()
    {
        if (!empty($this->start_at) && strpos($this->start_at, '-')) {
            $this->start_at = strtotime($this->start_at);
        }
        if (!empty($this->end_at) && strpos($this->end_at, '-')) {
            $this->end_at = strtotime($this->end_at);
        }

        return parent::beforeValidate();
    }
    */

    /**
     * {@inheritdoc}
     * @return ConfigBaseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConfigBaseQuery(get_called_class());
    }

    /**
     * beforeSave 存储数据库之前事件的实务的编排、注入；
     */ 
    public function beforeSave($insert)
    {
    	if(parent::beforeSave($insert))
    	{
                $admin_id = Yii::$app->user->getId();
    		if($insert)
    		{
                    $this->created_at = time();
                    $this->updated_at = time();
                    if($admin_id){
                       $this->created_id = $admin_id; 
                       $this->updated_id = $admin_id; 
                    }	
    		}
    		else 
    		{
                    $this->updated_at = time();
                    $this->updated_id= $admin_id;
    		}
    		return true;			
    	}
    	else 
    	{
    		return false;
    	}
    }
    /*
    * afterSave 保存之后的事件  示例
    */
//    public function afterSave($insert, $changedAttributes)
//    {
//        parent::afterSave($insert, $changedAttributes);
//        if ($insert) {
//            // 插入新数据之后修改订单状态
//            Order::updateAll(['shipping_status' => Order::SHIPPING_STATUS1, 'shipping_at' => time()], ['trade_no' => $this->order_trade_no]);
//        }
//    }
    
    /*
    * beforeDelete 删除之前事件 编排 ；  afterDelete 删除之后的事件编排  示例
    */
    
//    public function beforeDelete()
//    {
//        parent::beforeDelete();
//        
//    }
    /**
    * 保证数据事务完成，否则回滚
    */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE | self::OP_DELETE
            // self::SCENARIO_DEFAULT => self::OP_INSERT
        ];
    }
}
