<?php
/**
 * This is the model class for table "XportalBase";
 * @package backend\modules\xportal\models;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 20:29 */
namespace backend\modules\xportal\models;

use Yii;

/**
 * This is the model class for table "{{%xportal_base}}".
 *
 * @property int $base_id 网站id
 * @property string $base_sitename 网站名称
 * @property string $base_url 网站域名
 * @property string $base_keywords 网站的关键字
 * @property string $base_createtime 网站
 * @property string $base_sponser 网站公司名称
 * @property string $base_description 网站描述
 * @property string $base_address 公司地址
 * @property string $base_zip 公司邮编
 * @property string $base_tel 公司电话
 * @property string $base_email 公司邮箱
 * @property string $base_copyright 网站授权单
 * @property string $base_logo 网站LOGO
 * @property int $base_theme_id 网站模板id
 * @property string $base_egname
 * @property string $icp 备案号
 */
class XportalBase extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xportal_base}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['base_sitename', 'base_url'], 'required'],
            [['base_keywords', 'base_address', 'base_logo'], 'string'],
            [['base_createtime'], 'safe'],
            [['base_theme_id'], 'integer'],
            [['base_sitename', 'base_zip', 'base_email', 'base_copyright'], 'string', 'max' => 50],
            [['base_url', 'base_sponser'], 'string', 'max' => 100],
            [['base_description'], 'string', 'max' => 200],
            [['base_tel', 'icp'], 'string', 'max' => 30],
            [['base_egname'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'base_id' => Yii::t('app', '网站id'),
            'base_sitename' => Yii::t('app', '网站名称'),
            'base_url' => Yii::t('app', '网站域名'),
            'base_keywords' => Yii::t('app', '网站的关键字'),
            'base_createtime' => Yii::t('app', '网站'),
            'base_sponser' => Yii::t('app', '网站公司名称'),
            'base_description' => Yii::t('app', '网站描述'),
            'base_address' => Yii::t('app', '公司地址'),
            'base_zip' => Yii::t('app', '公司邮编'),
            'base_tel' => Yii::t('app', '公司电话'),
            'base_email' => Yii::t('app', '公司邮箱'),
            'base_copyright' => Yii::t('app', '网站授权单'),
            'base_logo' => Yii::t('app', '网站LOGO'),
            'base_theme_id' => Yii::t('app', '网站模板id'),
            'base_egname' => Yii::t('app', 'Base Egname'),
            'icp' => Yii::t('app', '备案号'),
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
     * @return XportalBaseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XportalBaseQuery(get_called_class());
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
