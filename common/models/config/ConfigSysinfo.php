<?php
/**
 * This is the model class for table "ConfigSysinfo";
 * @package common\models\config;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-05 16:43 */
namespace common\models\config;

use Yii;

/**
 * This is the model class for table "{{%config_sysinfo}}".
 *
 * @property int $siteid 站点ID
 * @property string $name 站点名
 * @property string $dirname 站点目录
 * @property string $domain 域名
 * @property string $serveralias 域名别名，多个以“,”分割
 * @property string $keywords SEO关键词
 * @property string $description 站点描述
 * @property string $site_point 发布点
 * @property int $smarty_id 使用的模板ID
 * @property int $smarty_app_id 移动端模板ID
 * @property string $address 站点主管单位地址
 * @property string $zipcode 站点主管单位邮编
 * @property string $tel 站点主管单位电话
 * @property string $fax 传真
 * @property string $email 站点主管单位负责人邮箱
 * @property string $copyright 站点主管单位
 * @property string $logo 站点Logo
 * @property string $banner 站点banner
 * @property string $reg_time 注册时间
 * @property string $begin_time 使用开始时间
 * @property string $end_time 使用结束时间，当前时间大于结束时间，status字段值0；如果为空则永久
 * @property string $basemail 邮箱名，用于发送邮件
 * @property string $mailpwd 邮箱密码，用户发送邮件
 * @property string $record 备案号
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property string $default_style 默认风格_主要指色调配色风格
 * @property string $contacts 联系人
 * @property string $comp_invoice 公司账户
 * @property string $comp_bank 公司开户行
 * @property string $bank_numb 银行账号
 */
class ConfigSysinfo extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%config_sysinfo}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['domain', 'keywords', 'description'], 'required'],
            [['smarty_id', 'smarty_app_id', 'created_at', 'updated_at'], 'integer'],
            [['address', 'logo', 'banner'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['dirname'], 'string', 'max' => 200],
            [['domain', 'comp_invoice', 'comp_bank'], 'string', 'max' => 100],
            [['serveralias', 'keywords', 'description'], 'string', 'max' => 255],
            [['site_point', 'zipcode', 'tel', 'fax', 'email', 'copyright', 'reg_time', 'record', 'default_style'], 'string', 'max' => 50],
            [['begin_time', 'end_time'], 'string', 'max' => 15],
            [['basemail', 'mailpwd'], 'string', 'max' => 60],
            [['contacts'], 'string', 'max' => 20],
            [['bank_numb'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'siteid' => Yii::t('app', '站点ID'),
            'name' => Yii::t('app', '站点名'),
            'dirname' => Yii::t('app', '站点目录'),
            'domain' => Yii::t('app', '域名'),
            'serveralias' => Yii::t('app', '域名别名，多个以“,”分割'),
            'keywords' => Yii::t('app', 'SEO关键词'),
            'description' => Yii::t('app', '站点描述'),
            'site_point' => Yii::t('app', '发布点'),
            'smarty_id' => Yii::t('app', '使用的模板ID'),
            'smarty_app_id' => Yii::t('app', '移动端模板ID'),
            'address' => Yii::t('app', '站点主管单位地址'),
            'zipcode' => Yii::t('app', '站点主管单位邮编'),
            'tel' => Yii::t('app', '站点主管单位电话'),
            'fax' => Yii::t('app', '传真'),
            'email' => Yii::t('app', '站点主管单位负责人邮箱'),
            'copyright' => Yii::t('app', '站点主管单位'),
            'logo' => Yii::t('app', '站点Logo'),
            'banner' => Yii::t('app', '站点banner'),
            'reg_time' => Yii::t('app', '注册时间'),
            'begin_time' => Yii::t('app', '使用开始时间'),
            'end_time' => Yii::t('app', '使用结束时间，当前时间大于结束时间，status字段值0；如果为空则永久'),
            'basemail' => Yii::t('app', '邮箱名，用于发送邮件'),
            'mailpwd' => Yii::t('app', '邮箱密码，用户发送邮件'),
            'record' => Yii::t('app', '备案号'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'default_style' => Yii::t('app', '默认风格_主要指色调配色风格'),
            'contacts' => Yii::t('app', '联系人'),
            'comp_invoice' => Yii::t('app', '公司账户'),
            'comp_bank' => Yii::t('app', '公司开户行'),
            'bank_numb' => Yii::t('app', '银行账号'),
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
     * @return ConfigSysinfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConfigSysinfoQuery(get_called_class());
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
