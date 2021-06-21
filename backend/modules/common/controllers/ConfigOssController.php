<?php

namespace backend\modules\common\controllers;

use Yii;
use common\models\config\ConfigOss;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use backend\controllers\BaseController;
//use app\...\helpers\RequestHelper;//根据情况引用RequestHelper助手类，并需要自行封装

/**
 * ConfigOssController implements the CRUD actions for ConfigOss model.
 */
class ConfigOssController extends BaseController
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
        $query = ConfigOss::getList($parames);
        $this->sidx = 'order';
        return $this->getJqTableData($query,"");
    }

    //添加
    public function actionCreate(){
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->post();
            $res = ConfigOss::createDo($request);
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
            $res = ConfigOss::updateDo($request);
            if($res === true){
                $this->returnSuccess('','success');
            }else{
                $this->returnError('',$res);
            }
        }else{
            $id = Yii::$app->request->get('id',"");
            $model = ConfigOss::findOne($id);
            return $this->render('update',['model'=>$model]);
        }
    }

    //删除 --修改is_del状态（0可用 1删除）
    public function actionDelete(){
        $id = Yii::$app->request->post('id',"");
        $model = ConfigOss::findOne($id);
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
        $model = ConfigOss::findOne($id);
        return $this->render('view',['model'=>$model]);
    }

    //批量删除父级目录
    public function actionDeleteAll(){
        $id = Yii::$app->request->get('id',"");
        $array = explode(',',$id);
        unset($array[0]);
        if(!empty($array)){
            foreach ($array as $key => $value) {
                $model = ConfigOss::findOne($value);
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
        $model = ConfigOss::findOne($id);
        
        $model->status = $status;
        if ($model->save()) {
            $this->returnSuccess('','success');
        } else {
            $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
    }

    //即点即改分页数量
    public function actionUpdateOrder(){
        $request = Yii::$app->request->post();
        $model = ConfigOss::findOne($request['id']);
        $model->order = $request['order'];
        if ($model->save()) {
            $this->returnSuccess('','success');
        } else {
            $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
    }
}