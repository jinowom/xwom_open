<?php
/**
 * This is the model class for table "ConfigBehaviorlog";
 * @package common\models\log;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2021-03-09 10:15 */
namespace common\models\log;

use Yii;

/**
 * This is the model class for table "config_behaviorlog".
 *
 * @property int $id 主键
 * @property int $merchant_id 商户id
 * @property string $app_id 应用id
 * @property int $user_id 用户id
 * @property int $behavior 行为类别 : 1.页面浏览 2.查询数据 3.增加数据 4.修改数据 5.删除数据
 * @property string $method 提交类型
 * @property string $module 模块
 * @property string $controller 控制器
 * @property string $action 控制器方法
 * @property string $url 提交url
 * @property string $get_data get数据
 * @property string $post_data post数据
 * @property string $header_data header数据
 * @property string $ip ip地址
 * @property string $addons_name 插件名称
 * @property string $remark 日志备注
 * @property string $country 国家
 * @property string $provinces 省
 * @property string $city 城市
 * @property string $device 设备信息
 * @property int $status 类型 0:全局； 1：前台；2：后台；3：API；
 * @property int $siteid 站点id
 * @property int $created_id 添加者
 * @property int $updated_id 修改者
 * @property int $updated_at 修改时间
 * @property int $created_at 添加时间
 * @property int $is_del 是否删除 0否 1是
 */
class ConfigBehaviorlog extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config_behaviorlog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'user_id', 'behavior', 'status', 'siteid', 'created_id', 'updated_id', 'updated_at', 'created_at', 'is_del'], 'integer'],
            [['get_data', 'post_data', 'header_data'], 'string'],
            [['app_id', 'module', 'controller', 'action', 'country', 'provinces', 'city'], 'string', 'max' => 50],
            [['method'], 'string', 'max' => 20],
            [['url', 'device'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 16],
            [['addons_name'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => 'Merchant ID',
            'app_id' => 'App ID',
            'user_id' => 'User ID',
            'behavior' => 'Behavior',
            'method' => 'Method',
            'module' => 'Module',
            'controller' => 'Controller',
            'action' => 'Action',
            'url' => 'Url',
            'get_data' => 'Get Data',
            'post_data' => 'Post Data',
            'header_data' => 'Header Data',
            'ip' => 'Ip',
            'addons_name' => 'Addons Name',
            'remark' => 'Remark',
            'country' => 'Country',
            'provinces' => 'Provinces',
            'city' => 'City',
            'device' => 'Device',
            'status' => 'Status',
            'siteid' => 'Siteid',
            'created_id' => 'Created ID',
            'updated_id' => 'Updated ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'is_del' => 'Is Del',
        ];
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
    // 获取列表
    public static function getList($parames){
        $behavior = isset($parames['behavior'])?$parames['behavior']:"";
        $status = isset($parames['status'])?$parames['status']:"";
        $parame = isset($parames['parames'])?$parames['parames']:"";
        return self::find()->andWhere(['is_del'=>0])
                           ->andFilterWhere(['behavior'=>$behavior,'status'=>$status])
                           ->andFilterWhere(['or',['like','user_id',$parame]
                                                 ,['like','module',$parame]   
                                                 ,['like','controller',$parame] 
                                                 ,['like','action',$parame]       
                                                 ,['like','ip',$parame]         
                                            ]);
    }
    // 提交添加表单
    public static function createDo($request){
        // 添加信息入库
        $model = new ConfigBehaviorlog();
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
