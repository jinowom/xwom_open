<?php
/**
 * This is the model class for table "ConfigApi";
 * @package common\models\config;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-07 11:38 */
namespace common\models\config;

use Yii;

/**
 * This is the model class for table "{{%config_api}}".
 *
 * @property int $id ID
 * @property string $name API名
 * @property string $name_en 英文名称
 * @property string $domain 请求域名
 * @property string $route 请求路由
 * @property string $type 请求模式1：post ；2：get
 * @property string $value 请求参数值
 * @property string $encryption_mode 加密模式：CBC
 * @property string $encryption_algorithm 加密算法：1:AES;2:RSA;3:Base64;4:MD5
 * @property int $is_sign 签名 1: yes ; 0:no
 * @property string $description 操作说明
 * @property int $status 状态 1: enable ; 0:disable
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 * @property int $created_id 添加者
 * @property int $updated_id 修改者
 */
class ConfigApi extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%config_api}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'name_en', 'domain', 'route', 'type', 'value', 'encryption_mode', 'encryption_algorithm', 'status'], 'required'],
            [['is_sign', 'status', 'created_at', 'updated_at', 'created_id', 'updated_id'], 'integer'],
            [['name', 'name_en', 'domain', 'route', 'type', 'value', 'encryption_mode', 'encryption_algorithm', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'API名'),
            'name_en' => Yii::t('app', '英文名称'),
            'domain' => Yii::t('app', '请求域名'),
            'route' => Yii::t('app', '请求路由'),
            'type' => Yii::t('app', '请求模式1：post ；2：get'),
            'value' => Yii::t('app', '请求参数值'),
            'encryption_mode' => Yii::t('app', '加密模式：CBC'),
            'encryption_algorithm' => Yii::t('app', '加密算法：1:AES;2:RSA;3:Base64;4:MD5'),
            'is_sign' => Yii::t('app', '签名 1: yes ; 0:no'),
            'description' => Yii::t('app', '操作说明'),
            'status' => Yii::t('app', '状态 1: enable ; 0:disable'),
            'created_at' => Yii::t('app', '添加时间'),
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
     * @return ConfigApiQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConfigApiQuery(get_called_class());
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
