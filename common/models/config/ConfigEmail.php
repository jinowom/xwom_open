<?php
/**
 * This is the model class for table "ConfigEmail";
 * @package common\models\config;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-07 12:07 */
namespace common\models\config;

use Yii;

/**
 * This is the model class for table "{{%config_email}}".
 *
 * @property int $id ID
 * @property string $scene 用途场景
 * @property string $email 邮箱名
 * @property string $smtp_host 邮件主机
 * @property string $smtp_account 邮件账户
 * @property string $smtp_password 邮件密码
 * @property string $smtp_port 邮件端口
 * @property string $encryp_mode 加密方式一般为tls
 * @property int $activation_type 是否属于邮件激活
 * @property int $token_time Token过期时间
 * @property int $status 状态 1: enable ; 0:disable
 * @property string $email_widget 邮件模板内容的动态数据提供Block部分
 * @property string $email_viewpatch 邮件模板内容的view部分
 * @property int $created_at 添加时间
 * @property int $updated_at
 * @property int $created_id 添加者
 * @property int $updated_id 修改者
 */
class ConfigEmail extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%config_email}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scene', 'email', 'smtp_host', 'smtp_account', 'smtp_password', 'smtp_port', 'encryp_mode', 'status'], 'required'],
            [['activation_type', 'token_time', 'status', 'created_at', 'updated_at', 'created_id', 'updated_id'], 'integer'],
            [['scene', 'email', 'smtp_host', 'smtp_account', 'smtp_password', 'smtp_port', 'encryp_mode', 'email_widget', 'email_viewpatch'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'scene' => Yii::t('app', '用途场景'),
            'email' => Yii::t('app', '邮箱名'),
            'smtp_host' => Yii::t('app', '邮件主机'),
            'smtp_account' => Yii::t('app', '邮件账户'),
            'smtp_password' => Yii::t('app', '邮件密码'),
            'smtp_port' => Yii::t('app', '邮件端口'),
            'encryp_mode' => Yii::t('app', '加密方式一般为tls'),
            'activation_type' => Yii::t('app', '是否属于邮件激活'),
            'token_time' => Yii::t('app', 'Token过期时间'),
            'status' => Yii::t('app', '状态 1: enable ; 0:disable'),
            'email_widget' => Yii::t('app', '邮件模板内容的动态数据提供Block部分'),
            'email_viewpatch' => Yii::t('app', '邮件模板内容的view部分'),
            'created_at' => Yii::t('app', '添加时间'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
     * @return ConfigEmailQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConfigEmailQuery(get_called_class());
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
