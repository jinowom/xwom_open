<?php
/**
 * This is the model class for table "ConfigIpmanage";
 * @package common\models\config;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2021-01-13 15:52 */
namespace common\models\config;

use Yii;

/**
 * This is the model class for table "config_ipmanage".
 *
 * @property int $id ID
 * @property string $ip IP地址
 * @property int $status 状态 1: enable ; 0:disable
 * @property int $start_time
 * @property int $end_time
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 * @property int $created_id 添加者
 * @property int $updated_id 修改者
 * @property int $is_del 是否删除[0:否;1:是]
 */
class ConfigIpmanage extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config_ipmanage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'start_time', 'end_time', 'created_at', 'updated_at', 'created_id', 'updated_id', 'is_del'], 'integer'],
            [['ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'status' => 'Status',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_id' => 'Created ID',
            'updated_id' => 'Updated ID',
            'is_del' => 'Is Del',
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
    public static function getList($parames)
    {
        return self::find()->select(['id', 'ip', 'status', 'from_unixtime(start_time,"%Y-%m-%d %H:%i:%s") start_time', 'from_unixtime(end_time,"%Y-%m-%d %H:%i:%s") end_time', 'created_at', 'updated_at', 'created_id', 'updated_id', 'is_del'])->Where(['is_del' => 0]);
    }
    // 提交添加表单
    public static function createDo($request){
        // 添加信息入库
        $model = new ConfigIpmanage();
        $model->attributes = $request;
        if ($model->save()) {
            return true;
        } else {
            return json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE);
        }
    }
    //提交修改表单
    public static function updateDo($request){
        $model = self::findOne($request['id']);
        $model->attributes = $request;
        if ($model->save()) {
            return true;
        } else {
            return json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE);
        }
    }
}
