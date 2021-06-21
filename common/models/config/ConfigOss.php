<?php
/**
 * This is the model class for table "ConfigOss";
 * @package common\models\config;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2021-01-21 13:30 */
namespace common\models\config;

use Yii;

/**
 * This is the model class for table "config_oss".
 *
 * @property int $id 主键
 * @property string $access_key_id 阿里ID
 * @property string $access_key_secret 阿里API密钥
 * @property string $bucket 阿里bucket域名
 * @property string $endpoint sdk配置项地域节点
 * @property string $url oss地址
 * @property string $local_url 本地地址
 * @property string $description 描述
 * @property int $status 状态[0:禁用;1:启用]
 * @property int $order 排序
 * @property int $created_id 添加者ID
 * @property int $updated_id 修改者ID
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 * @property int $site_id 站点ID
 * @property int $is_del 是否删除[0:否;1:是]
 */
class ConfigOss extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config_oss';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['access_key_id', 'access_key_secret', 'bucket', 'endpoint', 'url', 'local_url'], 'required'],
            [['status', 'order', 'created_id', 'updated_id', 'created_at', 'updated_at', 'site_id', 'is_del'], 'integer'],
            [['access_key_id'], 'string', 'max' => 80],
            [['access_key_secret', 'bucket', 'endpoint', 'url', 'local_url'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'access_key_id' => 'Access Key ID',
            'access_key_secret' => 'Access Key Secret',
            'bucket' => 'Bucket',
            'endpoint' => 'Endpoint',
            'url' => 'Url',
            'local_url' => 'Local Url',
            'description' => 'Description',
            'status' => 'Status',
            'order' => 'Order',
            'created_id' => 'Created ID',
            'updated_id' => 'Updated ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'site_id' => 'Site ID',
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
        // 添加信息入库
        $model = new ConfigOss();
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
