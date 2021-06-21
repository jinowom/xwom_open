<?php
/**
 * This is the model class for table "ConfigMessageMap";
 * @package common\models\config;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2021-01-14 14:49 */
namespace common\models\config;
use common\models\config\{ConfigMessage};
use common\models\User;
use api\models\XportalMember;
use Yii;

/**
 * This is the model class for table "config_message_map".
 *
 * @property int $id 短消息分配ID
 * @property int $message_id 短消息ID
 * @property int $user_id 用户ID（type为1：admin表；type为2：xportal_member表；）
 * @property int $user_type 用户类型  （1：前台；2：后台；）
 * @property int $is_read 是否阅读（0：未读；1：已读；）
 * @property int $read_time 阅读时间
 * @property int $siteid 站点id
 * @property int $created_id 添加者
 * @property int $updated_id 修改者
 * @property int $updated_at 修改时间
 * @property int $created_at 添加时间
 * @property int $is_del 是否删除 0否 1是
 *
 * @property ConfigMessage $message
 */
class ConfigMessageMap extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config_message_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_id', 'user_id'], 'required'],
            [['message_id', 'user_id', 'user_type', 'is_read', 'read_time', 'siteid', 'created_id', 'updated_id', 'updated_at', 'created_at', 'is_del'], 'integer'],
            [['message_id'], 'exist', 'skipOnError' => true, 'targetClass' => ConfigMessage::className(), 'targetAttribute' => ['message_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_id' => 'Message ID',
            'user_id' => 'User ID',
            'user_type' => 'User Type',
            'is_read' => 'Is Read',
            'read_time' => 'Read Time',
            'siteid' => 'Siteid',
            'created_id' => 'Created ID',
            'updated_id' => 'Updated ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'is_del' => 'Is Del',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessage()
    {
        return $this->hasOne(ConfigMessage::className(), ['id' => 'message_id']);
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
        $model =  self::find()->andWhere(['is_del'=>0])
                              ->andWhere(['message_id'=>$parames['id']]);
        $message = ConfigMessage::find()->select('title')
                                        ->andWhere(['is_del'=>0]) 
                                        ->andWhere(['id'=>$parames['id']])
                                        ->asArray()->one();
        $count = $model->count();                                           
        $list = $model->orderBy("created_at DESC")
                      ->offset(($parames['page'] - 1) * $parames['limit'])->limit($parames['limit'])
                      ->asArray()->all();
        if(!empty($list)){
            foreach ($list as $key => $val) {
                if($val['user_type'] == 1){ //前台
                    $user = XportalMember::find()->select('member_id, member_user as username')
                                                 ->andWhere(['member_id'=>$val['user_id']])
                                                 ->andWhere(['is_del'=>0])
                                                 ->asArray()->one();
                }else if($val['user_type'] == 2){//后台
                    $user = User::find()->select('user_id, username')
                                        ->andWhere(['user_id'=>$val['user_id']])
                                        ->asArray()->one();
                }
                if(!empty($user)){
                    $list[$key]['username'] = $user['username'];
                }
                $list[$key]['title'] = $message['title'];
                $list[$key]['read_time'] = date('Y-m-d H:i',$val['read_time']);
                $list[$key]['updated_at'] = date('Y-m-d H:i',$val['updated_at']);
                $list[$key]['created_at'] = date('Y-m-d H:i',$val['created_at']);
            }
        }
        return ['list'=>$list,'count'=>$count];
    }
    // 提交添加表单
    public static function createDo($request){
        // 添加信息入库
        $model = new ConfigMessageMap();
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
    //获取通知信息
    public static function getPubList(){
        $data = ConfigMessage::find()->andWhere(['is_del'=>0])
                                        ->andWhere(['is_send_all'=>0])
                                        ->andWhere(['status'=>1]);
        return $data;
    }

    //获取私信消息
    public static function getSelfList($user_type,$user_id=""){
        $model =  self::find()->from(ConfigMessageMap::tableName() . ' map')
                              ->select('map.id,map.user_id,map.is_read,map.created_at,map.updated_at,map.message_id,mes.title,mes.body')
                              ->leftJoin('config_message as mes','mes.id = map.message_id')
                              ->andWhere(['mes.is_send_all'=>1])
                              ->andWhere(['map.is_del'=>0,'mes.is_del'=>0])
                              ->andWhere(['map.user_type'=>$user_type])
                              ->andFilterWhere(['map.user_id'=>$user_id]);
        return $model;
    }
}
