<?php
/**
 * This is the model class for table "XportalThemeBigType";
 * @package backend\modules\xportal\models;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 16:37 */
namespace backend\modules\xportal\models;

use Yii;

/**
 * This is the model class for table "{{%xportal_theme_big_type}}".
 *
 * @property int $id 模板类id
 * @property string $big_type_name 模板类名称
 * @property string $typedir 模板类目录
 * @property int $listorder 模板类排序
 * @property string $description 模板类描述
 *
 * @property XportalCategory[] $xportalCategories
 * @property XportalChannel[] $xportalChannels
 * @property XportalTheme[] $xportalThemes
 * @property XportalThemeType[] $xportalThemeTypes
 */
class XportalThemeBigType extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xportal_theme_big_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['listorder'], 'integer'],
            [['big_type_name'], 'string', 'max' => 50],
            [['typedir'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '模板类id'),
            'big_type_name' => Yii::t('app', '模板类名称'),
            'typedir' => Yii::t('app', '模板类目录'),
            'listorder' => Yii::t('app', '模板类排序'),
            'description' => Yii::t('app', '模板类描述'),
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
        return $this->hasMany(XportalCategory::className(), ['module' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXportalChannels()
    {
        return $this->hasMany(XportalChannel::className(), ['channel_theme_type' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXportalThemes()
    {
        return $this->hasMany(XportalTheme::className(), ['big_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXportalThemeTypes()
    {
        return $this->hasMany(XportalThemeType::className(), ['big_type_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return XportalThemeBigTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XportalThemeBigTypeQuery(get_called_class());
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
