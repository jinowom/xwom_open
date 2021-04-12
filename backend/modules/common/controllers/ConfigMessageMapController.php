<?php

namespace backend\modules\common\controllers;

use Yii;
use common\models\config\{ConfigMessageMap};
use common\models\User;
use api\models\XportalMember;
use backend\controllers\BaseController;
class ConfigMessageMapController extends BaseController{
    //人员查看消息列表
    public function actionIndex(){
        return $this->render('index');
    }

    //获取通知信息
    public function actionPubList(){
        $this->pageSize = Yii::$app->request->get('limit',5);
        $this->page = Yii::$app->request->get('page',1);
        $query = ConfigMessageMap::getPubList();
        $this->sidx = 'created_at';
        return $this->getJqTableData($query,"");
    }

    //获取私信消息
    public function actionSelfList(){
        $this->pageSize = Yii::$app->request->get('limit',5);
        $this->page = Yii::$app->request->get('page',1);
        $query = ConfigMessageMap::getSelfList(2,$this->user_id);
        $this->sidx = 'map.created_at';
        return $this->getJqTableData($query,"");
    }

    //全部已读
    public function actionReadAll(){
        $res = ConfigMessageMap::updateAll(['is_read'=>1,'read_time'=>time()],['user_id'=>$this->user_id]);
        if($res){
            $this->returnSuccess('','success');
        }else{
            $this->returnError('',"已读失败");
        }
    }

    //阅读一个
    public function actionReadOne(){
        $id = Yii::$app->request->get('id',"");
        $model = ConfigMessageMap::findOne($id);
        $model->is_read = 1;
        $model->read_time = time();
        if ($model->save()) {
            $this->returnSuccess('','success');
        } else {
            $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
    }
}