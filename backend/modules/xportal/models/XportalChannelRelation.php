<?php
/**
 * This is the model class for table "XportalChannelRelation";
 * @package backend\modules\xportal\models;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 16:59 */
namespace backend\modules\xportal\models;

use Yii;

/**
 * This is the model class for table "{{%xportal_channel_relation}}".
 *
 * @property int $id id
 * @property int $channel_id 频道id
 * @property int $channel_father_id 父频道id
 * @property int $channel_distribution_id 分发id
 * @property int $channel_distribution_is 分发审核，1等待审核，2审核通过
 * @property int $distribution_listorder 频道导航排序
 * @property int $c_distribution_listorder 栏目导航排序
 */
class XportalChannelRelation extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xportal_channel_relation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['channel_id', 'channel_father_id', 'channel_distribution_id', 'channel_distribution_is', 'distribution_listorder', 'c_distribution_listorder'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'channel_id' => Yii::t('app', '频道id'),
            'channel_father_id' => Yii::t('app', '父频道id'),
            'channel_distribution_id' => Yii::t('app', '分发id'),
            'channel_distribution_is' => Yii::t('app', '分发审核，1等待审核，2审核通过'),
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
     * @return XportalChannelRelationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XportalChannelRelationQuery(get_called_class());
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
