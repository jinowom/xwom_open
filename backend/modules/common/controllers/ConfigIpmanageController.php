<?php

namespace backend\modules\common\controllers;

use Yii;
use common\models\config\ConfigIpmanage;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use backend\controllers\BaseController;
//use app\...\helpers\RequestHelper;//根据情况引用RequestHelper助手类，并需要自行封装

/**
 * ConfigIpmanageController implements the CRUD actions for ConfigIpmanage model.
 */
class ConfigIpmanageController extends BaseController
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
        $query = ConfigIpmanage::getList($parames);
        $this->sidx = 'created_at';
        return $this->getJqTableData($query,"");
    }

    //添加
    public function actionCreate(){
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->post();
            if(!empty($request['start_time'])){
                $request['start_time'] = strtotime($request['start_time']);
            }
            if(!empty($request['end_time'])){
                $request['end_time'] = strtotime($request['end_time']);
            }
            $res = ConfigIpmanage::createDo($request);
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
            if(!empty($request['start_time'])){
                $request['start_time'] = strtotime($request['start_time']);
            }
            if(!empty($request['end_time'])){
                $request['end_time'] = strtotime($request['end_time']);
            }
            $res = ConfigIpmanage::updateDo($request);
            if($res === true){
                $this->returnSuccess('','success');
            }else{
                $this->returnError('',$res);
            }
        }else{
            $id = Yii::$app->request->get('id',"");
            $model = ConfigIpmanage::findOne($id);
            if(!empty($model['start_time'])){
                $model['start_time'] = date('Y-m-d',$model['start_time']);
            }
            if(!empty($model['end_time'])){
                $model['end_time'] = date('Y-m-d',$model['end_time']);
            }
            return $this->render('update',['model'=>$model]);
        }
    }

    //删除 --修改is_del状态（0可用 1删除）
    public function actionDelete(){
        $id = Yii::$app->request->post('id',"");
        $model = ConfigIpmanage::findOne($id);
        $model->is_del = 1;
        if ($model->save()) {
            $this->returnSuccess('','success');
        } else {
            $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
    }
    
    //查看
    public function actionView(){
        $id = Yii::$app->request->get('id',"");
        $model = ConfigIpmanage::findOne($id);
        if(!empty($model['created_at'])){
            $model['created_at'] = date('Y-m-d',$model['created_at']);
        }
        if(!empty($model['updated_at'])){
            $model['updated_at'] = date('Y-m-d',$model['updated_at']);
        }
        if(!empty($model['start_time'])){
            $model['start_time'] = date('Y-m-d',$model['start_time']);
        }
        if(!empty($model['end_time'])){
            $model['end_time'] = date('Y-m-d',$model['end_time']);
        }
        if(!empty($model['status'])){
            $model['status'] = $model['status'] = 1 ? '启用' : '禁用';
        }
        return $this->render('view',['model'=>$model]);
    }

    //批量删除父级目录
    public function actionDeleteAll(){
        $id = Yii::$app->request->get('id',"");
        $array = explode(',',$id);
        unset($array[0]);
        if(!empty($array)){
            foreach ($array as $key => $value) {
                $model = ConfigIpmanage::findOne($value);
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
        $model = ConfigIpmanage::findOne($id);
        
        $model->status = $status;
        if ($model->save()) {
            $this->returnSuccess('','success');
        } else {
            $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
    }
}