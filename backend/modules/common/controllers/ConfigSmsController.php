<?php

namespace backend\modules\common\controllers;

use Yii;
use common\models\config\ConfigSms;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use backend\controllers\BaseController;
//use app\...\helpers\RequestHelper;//根据情况引用RequestHelper助手类，并需要自行封装

/**
 * ConfigSmsController implements the CRUD actions for ConfigSms model.
 */
class ConfigSmsController extends BaseController{

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
        $query = ConfigSms::getList($parames);
        $this->sidx = 'created_at';
        return $this->getJqTableData($query,"");
    }

    //添加
    public function actionCreate(){
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->post();
            $res = ConfigSms::createDo($request);
            if($res === true){
                $this->returnSuccess('','success');
            }else{
                $this->returnError('',$res);
            }
        }else{
            return $this->render('create');
        }
    }

    //修改使用状态
    public function actionUpdateStatus(){
        $id = Yii::$app->request->post('id',"");
        $status = Yii::$app->request->post('status',"0");
        $model = ConfigSms::findOne($id);
        if($status == 1){
            $exit = ConfigSms::find()->andWhere(['send_type' => $model->send_type , 'status'=>1, 'is_del'=>0])->one();
            if(!empty($exit)){
                return $this->returnError('','同一个发送类型的模板只能开启一个');
            }
        }
        $model->status = $status;
        if ($model->save()) {
            $this->returnSuccess('','success');
        } else {
            $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
    }

    //修改
    public function actionUpdate(){
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->post();
            $res = ConfigSms::updateDo($request);
            if($res === true){
                $this->returnSuccess('','success');
            }else{
                $this->returnError('',$res);
            }
        }else{
            $id = Yii::$app->request->get('id',"");
            $model = ConfigSms::findOne($id);
            $model->sendvariable = json_decode($model->sendvariable);
            return $this->render('update',['model'=>$model]);
        }
    }

    //删除 --修改is_del状态（0可用 1删除）
    public function actionDelete(){
        $id = Yii::$app->request->post('id',"");
        $exam = ConfigSms::find()->where(['is_del'=>0,'id'=>$id,'status'=>1])->one();
        if(!empty($exam)){
            $this->returnError('','该变量正在被使用不可删除');
        }else{
            $model = ConfigSms::findOne($id);
            $model->is_del = 1;
            if ($model->save()) {
                $this->returnSuccess('','success');
            } else {
                $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
            }
        }
    }
    
    //查看
    public function actionView(){
        $id = Yii::$app->request->get('id',"");
        $model = ConfigSms::findOne($id);
        return $this->render('view',['model'=>$model]);
    }

    //批量删除父级目录
    public function actionDeleteAll(){
        $id = Yii::$app->request->get('id',"");
        $array = explode(',',$id);
        unset($array[0]);
        if(!empty($array)){
            foreach ($array as $key => $value) {
                //判断有没有频道或者栏目在使用
                $isUser = ConfigSms::find()->where(['is_del'=>0,'id'=>$value,'status'=>1])->one();
                if(empty($isUser)) {
                    $model = ConfigSms::findOne($value);
                    $model->is_del = 1;
                    $model->save();
                }
            }   
            $this->returnSuccess('','success');
        }else{
            $this->returnError('','请选择要删除的数据');
        }
    }
}