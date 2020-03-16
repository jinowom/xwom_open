<?php

namespace backend\modules\common\models;

use Yii;

/**
 * This is the model class for table "wom_plan".
 *
 * @property int $id 记录ID
 * @property string $title 事件标题
 * @property string $desc 事件描述
 * @property int $status 状态[1 - 待处理 2 - 已委派 3 - 完成 4 延期]
 * @property int $time_status 事件状态[1 - 延缓 2 - 正常 3 - 紧急]
 * @property int $admin_id 委派管理员
 * @property int $start_at 开始时间
 * @property int $end_at 结束时间
 * @property int $created_at 创建时间
 * @property int $created_id 创建用户
 * @property int $updated_at 修改时间
 * @property int $updated_id 修改用户
 *
 * @property WomPlanStatus $status0
 * @property WomPlantimeStatus $timeStatus
 */
class WomPlan extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wom_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //使用filter来处理表单中时间的格式            
//            ['start_at' ,  'filter', 'filter' => function(){
//                return strtotime($this->start_at);
//            }],
//            ['end_at' ,  'filter', 'filter' => function(){
//                return strtotime($this->end_at);
//            }],
            [['status', 'time_status', 'admin_id', 'created_at', 'created_id', 'updated_at', 'updated_id'], 'integer'],
             [['start_at','end_at', ], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['desc'], 'string', 'max' => 255],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => WomPlanStatus::className(), 'targetAttribute' => ['status' => 'id']],
            [['time_status'], 'exist', 'skipOnError' => true, 'targetClass' => WomPlantimeStatus::className(), 'targetAttribute' => ['time_status' => 'id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '记录ID'),
            'title' => Yii::t('app', '事件标题'),
            'desc' => Yii::t('app', '事件描述'),
            'status' => Yii::t('app', '日程状态'),//[1 - 待处理 2 - 已委派 3 - 完成 4 延期]
            'time_status' => Yii::t('app', '事件状态'),//[1 - 延缓 2 - 正常 3 - 紧急]
            'admin_id' => Yii::t('app', '委派人员'),
            'start_at' => Yii::t('app', '开始时间'),
            'end_at' => Yii::t('app', '结束时间'),
            'created_at' => Yii::t('app', '创建时间'),
            'created_id' => Yii::t('app', '创建用户'),
            'updated_at' => Yii::t('app', '修改时间'),
            'updated_id' => Yii::t('app', '修改用户'),
        ];
    }
    // 验证之前的处理
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
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(WomPlanStatus::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeStatus()
    {
        return $this->hasOne(WomPlantimeStatus::className(), ['id' => 'time_status']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfo()
    {
        return $this->hasOne(\common\models\User::className(),['user_id' => 'admin_id']);
    }
    public function getCreatedInfo()
    {
        return $this->hasOne(\common\models\User::className(),['user_id' => 'created_id']);
    }
    public function getUpdatedInfo()
    {
        return $this->hasOne(\common\models\User::className(),['user_id' => 'updated_id']);
    }
    /**
     * getStatus() 获取状态信息
     *
     * @param null $intStatus 状态值
     *
     * @return array|mixed
     */
    public static function getStatusColors($intStatus = null)
    {
        $arrReturn = [
            self::STATUS_PENDING  => 'label-warning',
            self::STATUS_DELEGATE => 'label-info',
            self::STATUS_COMPLETE => 'label-success',
            self::STATUS_DEFER    => 'label-danger',
        ];

        if ($intStatus != null && isset($arrReturn[$intStatus])) {
            $arrReturn = $arrReturn[$intStatus];
        }

        return $arrReturn;
    }
    /**
     * {@inheritdoc}
     * @return WomPlanQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WomPlanQuery(get_called_class());
    }
    
    //接手任务计划
    public function approve()
    {
        if($this->status == 1){
            $this->admin_id = Yii::$app->user->getId();//设置委派人为当前账户的user_id为已接手
            $this->status = 2;//设置此任务为委派状态
            return ($this->save()?true:false);
        } else {
            echo "<script>alert('亲，此任务已委派，您来晚了哦！');history.go(-1);</script>";
            exit;
        }

    }
    //beforeSave 存储数据库之前事件的实务的编排、注入； 
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
    //afterSave 保存之后的事件  示例
//    public function afterSave($insert, $changedAttributes)
//    {
//        parent::afterSave($insert, $changedAttributes);
//        if ($insert) {
//            // 插入新数据之后修改订单状态
//            Order::updateAll(['shipping_status' => Order::SHIPPING_STATUS1, 'shipping_at' => time()], ['trade_no' => $this->order_trade_no]);
//        }
//    }
    
    //beforeDelete 删除之前事件 编排 ；  afterDelete 删除之后的事件编排  示例
    
//    public function beforeDelete()
//    {
//        parent::beforeDelete();
//        
//    }
    //保证数据事务完成，否则回滚
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE | self::OP_DELETE
            // self::SCENARIO_DEFAULT => self::OP_INSERT
        ];
    }
}
