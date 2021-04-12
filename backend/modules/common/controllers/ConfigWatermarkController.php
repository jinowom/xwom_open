<?php

namespace backend\modules\common\controllers;

use Yii;
use backend\modules\common\models\ConfigWatermark;
use backend\controllers\BaseController;
use common\models\UploadForm;
use yii\web\UploadedFile;

/**
 * ConfigWatermarkController implements the CRUD actions for ConfigWatermark model.
 */
class ConfigWatermarkController extends BaseController
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
        $query = ConfigWatermark::getList($parames);
        $this->sidx = 'created_at';
        return $this->getJqTableData($query,"");
    }

    //水印图上传
    public function actionUploadDo(){
        $imageFile = UploadedFile::getInstanceByName('file');
        $Upload = new UploadForm();
        $Upload->imageFile = $imageFile;
        $img = $Upload->upload('watemark','image');
        return $this->asJson(['code'=>'200','img'=>$img]);
    }

    //添加
    public function actionCreate(){
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->post();
            $res = ConfigWatermark::createDo($request);
            if($res === true){
                $this->returnSuccess('','success');
            }else{
                $this->returnError('',$res);
            }
        }else{
            return $this->render('create');
        }
    }

    //全局变量修改
    public function actionUpdate(){
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->post();
            $res = ConfigWatermark::updateDo($request);
            if($res === true){
                $this->returnSuccess('','success');
            }else{
                $this->returnError('',$res);
            }
        }else{
            $id = Yii::$app->request->get('id',"");
            $model = ConfigWatermark::findOne($id);
            return $this->render('update',['data'=>$model]);
        }
    }
    //修改使用状态
    public function actionUpdateStatus(){
        $id = Yii::$app->request->post('id',"");
        $status = Yii::$app->request->post('status',"0");
        $model = ConfigWatermark::findOne($id);
        $model->status = $status;
        if ($model->save()) {
            $this->returnSuccess('','success');
        } else {
            $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
    }
    //删除全局变量 --修改is_del状态（0可用 1删除）
    public function actionDelete(){
        $id = Yii::$app->request->post('id',"");
        $exam = ConfigWatermark::find()->where(['is_del'=>0,'id'=>$id,'status'=>1])->one();
        $model = ConfigWatermark::findOne($id);
        $model->is_del = 1;
        if(!empty($exam)){
            $this->returnError('','该变量正在被使用不可删除');
        }else{
            if ($model->save()) {
                $this->returnSuccess('','success');
            } else {
                $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
            }
        }
    }
}