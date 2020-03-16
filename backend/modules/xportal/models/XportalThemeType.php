<?php
/**
 * This is the model class for table "XportalThemeType";
 * @package backend\modules\xportal\models;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 16:36 */
namespace backend\modules\xportal\models;

use Yii;

/**
 * This is the model class for table "{{%xportal_theme_type}}".
 *
 * @property int $typeid 模板子类id
 * @property string $name 模板子类名称
 * @property string $typedir 模板子类目录
 * @property int $listorder 模板子类排序
 * @property string $description 模板子类描述
 * @property int $big_type_id 模板子类关联模板类id
 * @property int $c_c_c_id 模板子类关联模板频道栏目内容类id
 *
 * @property XportalTheme[] $xportalThemes
 * @property XportalCCC $cCC
 * @property XportalThemeBigType $bigType
 */
class XportalThemeType extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xportal_theme_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['listorder', 'big_type_id', 'c_c_c_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['typedir'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 255],
            [['c_c_c_id'], 'exist', 'skipOnError' => true, 'targetClass' => XportalCCC::className(), 'targetAttribute' => ['c_c_c_id' => 'id']],
            [['big_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => XportalThemeBigType::className(), 'targetAttribute' => ['big_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'typeid' => Yii::t('app', '模板子类id'),
            'name' => Yii::t('app', '模板子类名称'),
            'typedir' => Yii::t('app', '模板子类目录'),
            'listorder' => Yii::t('app', '模板子类排序'),
            'description' => Yii::t('app', '模板子类描述'),
            'big_type_id' => Yii::t('app', '模板子类关联模板类id'),
            'c_c_c_id' => Yii::t('app', '模板子类关联模板频道栏目内容类id'),
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
    public function getXportalThemes()
    {
        return $this->hasMany(XportalTheme::className(), ['typeid' => 'typeid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCCC()
    {
        return $this->hasOne(XportalCCC::className(), ['id' => 'c_c_c_id']);
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
     * @return XportalThemeTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XportalThemeTypeQuery(get_called_class());
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
