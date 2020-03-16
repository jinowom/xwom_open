<?php
/**
 * This is the model class for table "ConfigMutillang";
 * @package common\models\config;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-07 13:42 */
namespace common\models\config;

use Yii;

/**
 * This is the model class for table "{{%config_mutillang}}".
 *
 * @property int $id ID
 * @property string $name 语言名称
 * @property string $name_en 语言简称
 * @property string $description 操作说明
 * @property int $searchengine 搜索引擎
 * @property int $status 状态 1: enable ; 0:disable
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 * @property int $created_id 添加者
 * @property int $updated_id 修改者
 *
 * @property ConfigSearchengine $searchengine0
 */
class ConfigMutillang extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%config_mutillang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'name_en', 'searchengine', 'status'], 'required'],
            [['searchengine', 'status', 'created_at', 'updated_at', 'created_id', 'updated_id'], 'integer'],
            [['name', 'name_en', 'description'], 'string', 'max' => 255],
            [['searchengine'], 'exist', 'skipOnError' => true, 'targetClass' => ConfigSearchengine::className(), 'targetAttribute' => ['searchengine' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', '语言名称'),
            'name_en' => Yii::t('app', '语言简称'),
            'description' => Yii::t('app', '操作说明'),
            'searchengine' => Yii::t('app', '搜索引擎'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getSearchengine0()
    {
        return $this->hasOne(ConfigSearchengine::className(), ['id' => 'searchengine']);
    }

    /**
     * {@inheritdoc}
     * @return ConfigMutillangQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConfigMutillangQuery(get_called_class());
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
