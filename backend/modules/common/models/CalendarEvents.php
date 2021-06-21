<?php
/**
 * This is the model class for table "CalendarEvents";
 * @package backend\modules\common\models;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2021-01-21 11:58 */
namespace backend\modules\common\models;

use Yii;

/**
 * This is the model class for table "{{%calendar_events}}".
 *
 * @property int $id
 * @property string $title 名称
 * @property int $event_type 事件类型
 * @property string $description 内容
 * @property int $app_id ID
 * @property int $site_id ID
 * @property int $admin_id 操作员ID
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $status 状态
 */
class CalendarEvents extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%calendar_events}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'app_id', 'site_id'], 'required'],
            [['event_type', 'app_id', 'site_id', 'admin_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', '名称'),
            'event_type' => Yii::t('app', '事件类型'),
            'description' => Yii::t('app', '内容'),
            'app_id' => Yii::t('app', 'ID'),
            'site_id' => Yii::t('app', 'ID'),
            'admin_id' => Yii::t('app', '操作员ID'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'status' => Yii::t('app', '状态'),
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
     * @return CalendarEventsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CalendarEventsQuery(get_called_class());
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
    /**
    * @var array 开关变量字段示例，如果已经开启，需要把字段赋值以数组形式列出
    */
   //public $switchValues = [
   //    'status' => [
   //        'on' => 1,
   //        'off' => 0,
   //    ],
   //    'emphasis' => [
   //        'on' => 1,
   //        'off' => 0,
   //    ],
   //   //也可以是非 1，0 譬如，如下
   //   'isRecommend' => [
   //     'on' => 10,
   //     'off' => 0,
   //   ],
   //];
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
    // 获取列表
    public static function getList($parames){
        return self::find()->andWhere(['is_del'=>0]);
    }
}
