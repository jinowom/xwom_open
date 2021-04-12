<?php
/**
 * This is the model class for table "ConfigSms";
 * @package common\models\config;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2021-01-13 12:10 */
namespace common\models\config;

use Yii;

/**
 * This is the model class for table "config_sms".
 *
 * @property int $id 主键
 * @property int $order 排序
 * @property string $sdk_com 短信提供商
 * @property string $description 描述
 * @property string $access_key_id 短信APIP:ID
 * @property string $access_key_secret 短信API:密钥
 * @property string $access_key_sign 签名
 * @property string $model_id 模板
 * @property string $send_message 内容示例:短信提供商审核后
 * @property string $sendvariable 内容的变量函数
 * @property int $created_id 添加者ID
 * @property int $updated_id 修改者ID
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 * @property int $status 状态[0:禁用;1:启用]
 * @property int $site_id 站点ID
 * @property int $app_id 应用ID
 * @property int $is_del 是否删除[0:否;1:是]
 */
class ConfigSms extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config_sms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order', 'created_id', 'updated_id', 'created_at', 'updated_at', 'status', 'site_id', 'app_id', 'is_del','sdk_type', 'send_type'], 'integer'],
            [['access_key_id', 'access_key_secret', 'access_key_sign', 'model_id', 'send_message'], 'required'],
            [['sendvariable'], 'string'],
            [['sdk_com', 'access_key_id'], 'string', 'max' => 80],
            [['description', 'send_message'], 'string', 'max' => 255],
            [['access_key_secret', 'access_key_sign'], 'string', 'max' => 100],
            [['model_id'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sdk_type' => "Sdk Type",
            'send_type' => "Send Type",
            'order' => 'Order',
            'sdk_com' => 'Sdk Com',
            'description' => 'Description',
            'access_key_id' => 'Access Key ID',
            'access_key_secret' => 'Access Key Secret',
            'access_key_sign' => 'Access Key Sign',
            'model_id' => 'Model ID',
            'send_message' => 'Send Message',
            'sendvariable' => 'Sendvariable',
            'created_id' => 'Created ID',
            'updated_id' => 'Updated ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'site_id' => 'Site ID',
            'app_id' => 'App ID',
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
    public static function getList($parames){
        return self::find()->andWhere(['is_del'=>0]);
    }
    // 提交添加表单
    public static function createDo($request){
        $request['sendvariable'] = json_encode($request['key'],JSON_UNESCAPED_UNICODE);
        // 添加信息入库
        $model = new ConfigSms();
        $model->attributes = $request;
        if ($model->save()) {
            return true;
        } else {
            return json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE);
        }
    }
    //提交修改表单
    public static function updateDo($request){
        $request['sendvariable'] = json_encode($request['key'],JSON_UNESCAPED_UNICODE);
        $model = self::findOne($request['id']);
        $model->attributes = $request;
        if ($model->save()) {
            return true;
        } else {
            return json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE);
        }
    }
}
