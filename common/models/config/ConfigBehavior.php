<?php
/**
 * This is the model class for table "ConfigBehavior";
 * @package common\models\config;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-06 18:45 */
namespace common\models\config;

use Yii;

/**
 * This is the model class for table "{{%config_behavior}}".
 *
 * @property int $id 主键
 * @property string $app_id 应用id
 * @property string $url 提交url
 * @property string $method 提交类型 *为不限
 * @property string $behavior 行为类别
 * @property int $action 前置/后置
 * @property string $level 级别
 * @property int $is_record_post 是否记录post[0;否;1是]
 * @property int $is_ajax 是否ajax请求[1;否;2是;3不限]
 * @property string $remark 备注
 * @property string $addons_name 插件名称
 * @property int $is_addon 是否插件
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 * @property int $created_id 添加者
 * @property int $updated_id 修改者
 */
class ConfigBehavior extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%config_behavior}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action', 'is_record_post', 'is_ajax', 'is_addon', 'status', 'created_at', 'updated_at', 'created_id', 'updated_id'], 'integer'],
            [['app_id', 'behavior'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 200],
            [['method', 'level'], 'string', 'max' => 20],
            [['remark', 'addons_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键'),
            'app_id' => Yii::t('app', '应用id'),
            'url' => Yii::t('app', '提交url'),
            'method' => Yii::t('app', '提交类型 *为不限'),
            'behavior' => Yii::t('app', '行为类别'),
            'action' => Yii::t('app', '前置/后置'),
            'level' => Yii::t('app', '级别'),
            'is_record_post' => Yii::t('app', '是否记录post[0;否;1是]'),
            'is_ajax' => Yii::t('app', '是否ajax请求[1;否;2是;3不限]'),
            'remark' => Yii::t('app', '备注'),
            'addons_name' => Yii::t('app', '插件名称'),
            'is_addon' => Yii::t('app', '是否插件'),
            'status' => Yii::t('app', '状态[-1:删除;0:禁用;1启用]'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '修改时间'),
            'created_id' => Yii::t('app', '添加者'),
            'updated_id' => Yii::t('app', '修改者'),
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
     * @return ConfigBehaviorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConfigBehaviorQuery(get_called_class());
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
