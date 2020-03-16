<?php
/**
 * This is the model class for table "XportalCategoryBind";
 * @package backend\modules\xportal\models;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 17:15 */
namespace backend\modules\xportal\models;

use Yii;

/**
 * This is the model class for table "{{%xportal_category_bind}}".
 *
 * @property int $id id
 * @property int $category_id 栏目id
 * @property int $parentid 父频道id
 * @property int $arrparentid 分发频道id
 * @property int $category_arrparent_is 分发审核，1等待审核，2审核通过
 * @property int $distribution_listorder 频道导航排序
 * @property int $c_distribution_listorder 栏目导航排序
 */
class XportalCategoryBind extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xportal_category_bind}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'parentid', 'arrparentid', 'category_arrparent_is', 'distribution_listorder', 'c_distribution_listorder'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'category_id' => Yii::t('app', '栏目id'),
            'parentid' => Yii::t('app', '父频道id'),
            'arrparentid' => Yii::t('app', '分发频道id'),
            'category_arrparent_is' => Yii::t('app', '分发审核，1等待审核，2审核通过'),
            'distribution_listorder' => Yii::t('app', '频道导航排序'),
            'c_distribution_listorder' => Yii::t('app', '栏目导航排序'),
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
     * @return XportalCategoryBindQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XportalCategoryBindQuery(get_called_class());
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
