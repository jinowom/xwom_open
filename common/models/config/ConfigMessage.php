<?php
/**
 * This is the model class for table "ConfigMessage";
 * @package common\models\config;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2021-01-13 17:20 */
namespace common\models\config;
use common\models\config\{ConfigMessageMap};
use jinostart\workflow\manager\models\{Metadata,Status};
use services\SendSmsService;
use common\models\User;
use common\models\auth\{AuthItemChild};
use common\utils\{LogUtil};

use Yii;

/**
 * This is the model class for table "config_message".
 *
 * @property int $id 短消息ID
 * @property string $title 短消息标题
 * @property string $body 短消息内容
 * @property int $is_send_all 是否发给所有人  0否 1是
 * @property int $status 状态 1: 开启; 0:关闭
 * @property int $listorder 排序（优先级）
 * @property int $siteid 站点id
 * @property int $created_id 添加者
 * @property int $updated_id 修改者
 * @property int $updated_at 修改时间
 * @property int $created_at 添加时间
 * @property int $is_del 是否删除 0否 1是
 *
 * @property ConfigMessageMap[] $configMessageMaps
 */
class ConfigMessage extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_send_all', 'status', 'listorder', 'siteid', 'created_id', 'updated_id', 'updated_at', 'created_at', 'is_del'], 'integer'],
            [['title', 'body'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'body' => 'Body',
            'is_send_all' => 'Is Send All',
            'status' => 'Status',
            'listorder' => 'Listorder',
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
    public function getConfigMessageMaps()
    {
        return $this->hasMany(ConfigMessageMap::className(), ['message_id' => 'id']);
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
        return self::find()->andWhere(['is_del'=>0])->andWhere(['like','title',$parames]);
    }
    // 提交添加表单
    public static function createDo($request){
        // 添加信息入库
        $trans = \Yii::$app->db->beginTransaction();
        try{
            $model = new ConfigMessage();
            $model->attributes = $request;
            $mes_date = [];
            if ($model->save()) {
                if(!empty($request['member_id'])){
                    foreach ($request['member_id'] as $key => $value) {
                        $mes_data['message_id'] = $model->id;
                        $mes_data['user_id'] = $value;
                        $mes_data['user_type'] = 1;
                        $res = ConfigMessageMap::createDo($mes_data);
                        if($res !== true){
                            $trans->rollBack();
                            return $res;
                        }
                        $mes_date = [];
                    }
                }
                if(!empty($request['user_id'])){
                    foreach ($request['user_id'] as $key => $value) {
                        $mes_data['message_id'] = $model->id;
                        $mes_data['user_id'] = $value;
                        $mes_data['user_type'] = 2;
                        $res = ConfigMessageMap::createDo($mes_data);
                        if($res !== true){
                            $trans->rollBack();
                            return $res;
                        }
                        $mes_date = [];
                    }
                }
                $trans->commit();
                return true;
            } else {
                $trans->rollBack();
                return json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE);
            }
        }catch (\Exception $e){
            $trans->rollBack();
            LogUtil::setExceptionLog('create 站内短消息',$e);
            return '操作失败';
        }
    }
    //提交修改表单
    public static function updateDo($request){
         // 添加信息入库
         $trans = \Yii::$app->db->beginTransaction();
         try{
             $model = self::findOne($request['id']);
             $model->attributes = $request;
             $mes_data = [];
             if ($model->save()) {
                ConfigMessageMap::deleteAll(['message_id' => $model->id]);
                 if(!empty($request['member_id'])){
                     foreach ($request['member_id'] as $key => $value) {
                         $mes_data['message_id'] = $model->id;
                         $mes_data['user_id'] = $value;
                         $mes_data['user_type'] = 1;
                         $res = ConfigMessageMap::createDo($mes_data);
                         if($res !== true){
                              $trans->rollBack();
                              return $res;
                          }
                         $mes_data = [];
                     }
                 }
                 if(!empty($request['user_id'])){
                     foreach ($request['user_id'] as $key => $value) {
                         $mes_data['message_id'] = $model->id;
                         $mes_data['user_id'] = $value;
                         $mes_data['user_type'] = 2;
                         $res = ConfigMessageMap::createDo($mes_data);
                         if($res !== true){
                            $trans->rollBack();
                            return $res;
                         }
                         $mes_data = [];
                     }
                 }
                 $trans->commit();
                 return true;
             } else {
                 $trans->rollBack();
                 return json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE);
             }
         }catch (\Exception $e){
             $trans->rollBack();
             LogUtil::setExceptionLog('create 站内短消息',$e);
             return '操作失败';
         }
    }

    //将消息发送给持有某个权限的人
    public static function sndMessage($user_id,$power){
       $user = User::findOne(['user_id'=>$user_id]);
       $userName = $user->real_name;
       $Status = Status::find()->where(['id'=>$power])->asArray()->one();
       $role =  AuthItemChild::find()->select('parent')->where(['child'=>$power])->asArray()->all();
       if(empty($role) || empty($Status)){
           return true;
       }
       $sendData['status'] = $Status['label'];
       $sendData['userName'] = $userName;
       $userDate = [];
       $phone = [];
       //组中用户数据
       foreach ($role as $key => $value) {
          $data = User::find()->select('user_id, phone')
                              ->andWhere(['in','role_id',$value['parent']])
                              ->andWhere(['status'=>10])
                              ->asArray()->all();
          if(!empty($data)){
            foreach ($data as $key => $v) {
                array_push($userDate, $v['user_id']);
                //往手机推送短消息
                SendSmsService::getInformSms($v['phone'],$sendData);
            }
          }
       }
       $title ='文稿待处理提醒';
       $body = "您有".$userName."提交的".$Status['label']."文章待处理。";
       $request['user_id'] = $userDate;
       $request['title'] = $title;
       $request['body'] = $body;
       $request['is_send_all'] = 1;
       $res = self::createDo($request);
       return $res;
    }
}
