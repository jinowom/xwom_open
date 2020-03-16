<?php
/**
 * This is the model class for table "ConfigBehaviorlog";
 * @package common\models\log;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-07 15:18 */
namespace common\models\log;

use Yii;

/**
 * This is the model class for table "{{%config_behaviorlog}}".
 *
 * @property int $id 主键
 * @property int $merchant_id 商户id
 * @property string $app_id 应用id
 * @property int $user_id 用户id
 * @property string $behavior 行为类别
 * @property string $method 提交类型
 * @property string $module 模块
 * @property string $controller 控制器
 * @property string $action 控制器方法
 * @property string $url 提交url
 * @property array $get_data get数据
 * @property array $post_data post数据
 * @property array $header_data header数据
 * @property string $ip ip地址
 * @property string $addons_name 插件名称
 * @property string $remark 日志备注
 * @property string $country 国家
 * @property string $provinces 省
 * @property string $city 城市
 * @property string $device 设备信息
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class ConfigBehaviorlog extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%config_behaviorlog}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['get_data', 'post_data', 'header_data'], 'safe'],
            [['app_id', 'behavior', 'module', 'controller', 'action', 'country', 'provinces', 'city'], 'string', 'max' => 50],
            [['method'], 'string', 'max' => 20],
            [['url', 'device'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 16],
            [['addons_name'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键'),
            'merchant_id' => Yii::t('app', '商户id'),
            'app_id' => Yii::t('app', '应用id'),
            'user_id' => Yii::t('app', '用户id'),
            'behavior' => Yii::t('app', '行为类别'),
            'method' => Yii::t('app', '提交类型'),
            'module' => Yii::t('app', '模块'),
            'controller' => Yii::t('app', '控制器'),
            'action' => Yii::t('app', '控制器方法'),
            'url' => Yii::t('app', '提交url'),
            'get_data' => Yii::t('app', 'get数据'),
            'post_data' => Yii::t('app', 'post数据'),
            'header_data' => Yii::t('app', 'header数据'),
            'ip' => Yii::t('app', 'ip地址'),
            'addons_name' => Yii::t('app', '插件名称'),
            'remark' => Yii::t('app', '日志备注'),
            'country' => Yii::t('app', '国家'),
            'provinces' => Yii::t('app', '省'),
            'city' => Yii::t('app', '城市'),
            'device' => Yii::t('app', '设备信息'),
            'status' => Yii::t('app', '状态[-1:删除;0:禁用;1启用]'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '修改时间'),
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
     * @return ConfigBehaviorlogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConfigBehaviorlogQuery(get_called_class());
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
