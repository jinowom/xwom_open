<?php
/**
 * 全局方法
*/
use common\models\config\ConfigVariable;
use common\models\log\ConfigBehaviorlog;
use api\models\XportalMember;
use common\helpers\ArrayHelper;
/**
 * Notes:   获取全局变量
 * @param   $name   string  变量名
 * @param   $type      int  变量类型 0:全局； 1：前台；2：后台；3：API；
 */
function getVar($name="",$type=0){
    $data =  ConfigVariable::find()->select('value')
                                   ->andWhere(['status'=>1])
                                   ->andWhere(['is_del'=>0,'name_en'=>$name,'type'=>$type])
                                   ->asarray()->one();
    if(!empty($data)){
        return $data['value'];
    }else{
        return false;
    }
}


/**
 * Notes: 系统行为日志写入方法
 * @param   $status      int  类型 0:全局； 1：前台；2：后台；3：API；
 * @param   $behavior   int  行为类别 : 1.页面浏览 2.查询数据 3.增加数据 4.修改数据 5.删除数据
 * @param   $remark     string  日志备注
 */
function userLog($status = 0 , $behavior = 1 , $remark = ""){
    $request = Yii::$app->request;
    $model = new ConfigBehaviorlog();
    if($status != 3){
        $user = Yii::$app->user;
        $model->user_id =  $user->identity->UserID;
    } else {
        // $getMember = XportalMember::findIdentityByAccessToken($request->headers['access-token']);
        // $model->user_id = $getMember->member_id;
    }
    $headers = ArrayHelper::object_array($request->headers);
    $model->behavior = $behavior;
    $model->status = $status;
    $model->method = $request->method;
    $model->controller = Yii::$app->controller->id;
    $model->action = Yii::$app->controller->action->id;
    $model->url = $request->getUrl();
    $model->get_data = json_encode($request->get(),JSON_UNESCAPED_UNICODE);
    $model->post_data = json_encode($request->post(),JSON_UNESCAPED_UNICODE);
    $model->header_data = json_encode($headers,JSON_UNESCAPED_UNICODE);
    $model->ip = $request->getUserIP();
    $model->remark = $remark;
    
    $model->save();
    // print_r($model->getErrors());exit;
}


