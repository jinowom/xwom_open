<?php

namespace backend\modules\common\controllers;

use Yii;
use common\models\config\{ConfigMessageMap,ConfigMessage};
use common\models\User;
use api\models\XportalMember;
use backend\controllers\BaseController;
//use app\...\helpers\RequestHelper;//根据情况引用RequestHelper助手类，并需要自行封装

/**
 * ConfigMessageController implements the CRUD actions for ConfigMessage model.
 */
class ConfigMessageController extends BaseController
{
    public function actionIndex(){
        if (Yii::$app->request->isAjax) {
            $this->getList();
        }else{
            return $this->render('index');
        }
    }

    //获取列表
    public function getList(){
        $this->pageSize = Yii::$app->request->get('limit',5);
        $this->page = Yii::$app->request->get('page',1);
        $parames = Yii::$app->request->get('parames',"");
        $query = ConfigMessage::getList($parames);
        $this->sidx = 'created_at';
        return $this->getJqTableData($query,"");
    }

    //添加
    public function actionCreate(){
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->post();
            $res = ConfigMessage::createDo($request);
            if($res === true){
                $this->returnSuccess('','success');
            }else{
                $this->returnError('',$res);
            }
        }else{
            return $this->render('create');
        }
    }

    //修改
    public function actionUpdate(){
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->post();
            $res = ConfigMessage::updateDo($request);
            if($res === true){
                $this->returnSuccess('','success');
            }else{
                $this->returnError('',$res);
            }
        }else{
            $id = Yii::$app->request->get('id',"");
            $model = ConfigMessage::findOne($id);
            $member = [];
            $user = [];
            $member_id = ConfigMessageMap::find()->select('user_id')->andWhere(['message_id'=>$model->id, 'user_type'=>1, 'is_del' =>0])->asArray()->all();
            if(!empty($member_id)){
                foreach ($member_id as $key => $value) {
                    $member[] = $value['user_id'];
                }
            }
            $user_id = ConfigMessageMap::find()->select('user_id')->andWhere(['message_id'=>$model->id, 'user_type'=>2, 'is_del' =>0])->asArray()->all();
            if(!empty($user_id)){
                foreach ($user_id as $key => $value) {
                    $user[] = $value['user_id'];
                }
            }
            $user_data = User::find()->select('user_id, username, real_name')->asArray()->all();
            $member_data = XportalMember::find()->select('member_id, member_user, member_name')->asArray()->all();
            return $this->render('update',['model'=>$model,'member'=>$member, 'user'=>$user,'user_data'=>$user_data,'member_data'=>$member_data]);
        }
    }

    //删除 --修改is_del状态（0可用 1删除）
    public function actionDelete(){
        $id = Yii::$app->request->post('id',"");
        $model = ConfigMessage::findOne($id);
        $model->is_del = 1;
        if ($model->save()) {
            ConfigMessageMap::deleteAll(['message_id' => $model->id]);
            $this->returnSuccess('','success');
        } else {
            $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
    }
    
    //查看
    public function actionView(){
        $id = Yii::$app->request->get('id',"");
        if (Yii::$app->request->isAjax) {
            $this->getMapList();
        }else{
            return $this->render('view',['id'=>$id]);
        }
    }

    //批量删除父级目录
    public function actionDeleteAll(){
        $id = Yii::$app->request->get('id',"");
        $array = explode(',',$id);
        unset($array[0]);
        if(!empty($array)){
            foreach ($array as $key => $value) {
                $model = ConfigMessage::findOne($value);
                $model->is_del = 1;
                $model->save();
            }   
            $this->returnSuccess('','success');
        }else{
            $this->returnError('','请选择要删除的数据');
        }
    }
    //修改使用状态
    public function actionUpdateStatus(){
        $id = Yii::$app->request->post('id',"");
        $status = Yii::$app->request->post('status',"0");
        $model = ConfigMessage::findOne($id);
        $model->status = $status;
        if ($model->save()) {
            $this->returnSuccess('','success');
        } else {
            $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
    }

    //获取系统用户信息
    public function actionGetAdminInfo(){
       $data = User::find()->select('user_id, username, real_name')->asArray()->all();
       return $this->returnSuccess($data,'success');
    }

    //获取会员用户信息
    public function actionGetMemberInfo(){
        $data = XportalMember::find()->select('member_id, member_user, member_name')->asArray()->all();
        return $this->returnSuccess($data,'success');
    }
    
    //获取列表
    public function getMapList(){
        $parames = $this->pageSize = Yii::$app->request->get();
        $model = ConfigMessageMap::getList($parames);
        return $this->asJson([
            'code' => 0,
            'msg' => 'ok',
            'count' => $model['count'],
            'data' => $model['list']
        ]);
    }
}