<?php
/**
 * This is the model class for table "XportalTheme";
 * @package backend\modules\xportal\models;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 16:49 */
namespace backend\modules\xportal\models;

use Yii;

/**
 * This is the model class for table "{{%xportal_theme}}".
 *
 * @property int $theme_id 模板id
 * @property int $typeid 关联模板子类id
 * @property int $big_type_id 关联模板类id
 * @property string $theme_name 模板名称
 * @property string $theme_dir 模板目录
 * @property string $theme_image 模板平面图目录
 * @property int $theme_listorder 模板排序
 * @property string $description 模板描述
 * @property int $index_theme 模板首页是否使用1使用
 * @property int $siteid 站点id
 * @property int $created_id 添加者
 * @property int $updated_id 修改者
 * @property int $updated_at 添加者
 * @property int $created_at 修改者
 *
 * @property XportalCategory[] $xportalCategories
 * @property XportalCategory[] $xportalCategories0
 * @property XportalChannel[] $xportalChannels
 * @property XportalThemeType $type
 * @property XportalThemeBigType $bigType
 */
class XportalTheme extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xportal_theme}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['typeid', 'big_type_id', 'theme_listorder', 'index_theme', 'siteid', 'created_id', 'updated_id', 'updated_at', 'created_at'], 'integer'],
            [['theme_name'], 'string', 'max' => 50],
            [['theme_dir', 'theme_image'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 255],
            [['typeid'], 'exist', 'skipOnError' => true, 'targetClass' => XportalThemeType::className(), 'targetAttribute' => ['typeid' => 'typeid']],
            [['big_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => XportalThemeBigType::className(), 'targetAttribute' => ['big_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'theme_id' => Yii::t('app', '模板id'),
            'typeid' => Yii::t('app', '关联模板子类id'),
            'big_type_id' => Yii::t('app', '关联模板类id'),
            'theme_name' => Yii::t('app', '模板名称'),
            'theme_dir' => Yii::t('app', '模板目录'),
            'theme_image' => Yii::t('app', '模板平面图目录'),
            'theme_listorder' => Yii::t('app', '模板排序'),
            'description' => Yii::t('app', '模板描述'),
            'index_theme' => Yii::t('app', '模板首页是否使用1使用'),
            'siteid' => Yii::t('app', '站点id'),
            'created_id' => Yii::t('app', '添加者'),
            'updated_id' => Yii::t('app', '修改者'),
            'updated_at' => Yii::t('app', '添加者'),
            'created_at' => Yii::t('app', '修改者'),
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
    public function getXportalCategories()
    {
        return $this->hasMany(XportalCategory::className(), ['category_theme' => 'theme_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXportalCategories0()
    {
        return $this->hasMany(XportalCategory::className(), ['temparticle' => 'theme_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXportalChannels()
    {
        return $this->hasMany(XportalChannel::className(), ['channel_theme_id' => 'theme_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(XportalThemeType::className(), ['typeid' => 'typeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBigType()
    {
        return $this->hasOne(XportalThemeBigType::className(), ['id' => 'big_type_id']);
    }

    /**
     * {@inheritdoc}
     * @return XportalThemeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XportalThemeQuery(get_called_class());
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
