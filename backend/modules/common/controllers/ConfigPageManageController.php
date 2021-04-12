<?php
/**
 * Class name  is ConfigPageManageController * @package backend\modules\common\controllers;
 * @author wuhaibo  email:1316177185@qq.com
 * @DateTime 2020-12-07 15:24 
 */
namespace backend\modules\common\controllers;

use Yii;
use backend\controllers\BaseController;
use  backend\modules\common\models\ConfigPageManage;
use common\models\reg\RegSoftware;

/**
 * 全局分页配置
 */
class ConfigPageManageController extends BaseController{
    //设置全局便令首页
    public function actionIndex(){
        if (Yii::$app->request->isAjax) {
            $this->getConfigPageLest();
        }else{
            return $this->render('index');
        }
    }
    //获取全局分页列表
    public function getConfigPageLest(){
        $this->pageSize = Yii::$app->request->get('limit',5);
        $this->page = Yii::$app->request->get('page',1);
        $parames = Yii::$app->request->get('parames',"");
        $query = ConfigPageManage::getPageList($parames);
        $this->sidx = 'page.created_at';
        return $this->getJqTableData($query,"");
    }
    //添加全局分页配置
    public function actionCreate(){
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->post();
            $res = ConfigPageManage::createDo($request);
            if($res === true){
                $this->returnSuccess('','success');
            }else{
                $this->returnError('',$res);
            }
        }else{
            $reg_soft = RegSoftware::find()->select('id,title')->asArray()->all();
            return $this->render('create',['reg_soft'=>$reg_soft]);
        }
    }
    //全局分页配置管理修改
    public function actionUpdate(){
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->post();
            $res = ConfigPageManage::updateDo($request);
            if($res === true){
                $this->returnSuccess('','success');
            }else{
                $this->returnError('',$res);
            }
        }else{
            $id = Yii::$app->request->get('id',"");
            $model = ConfigPageManage::findOne($id);
            $reg_soft = RegSoftware::find()->select('id,title')->asArray()->all();
            return $this->render('update',['model'=>$model,'reg_soft'=>$reg_soft]);
        }
    }
    //修改使用状态
    public function actionUpdateStatus(){
        $id = Yii::$app->request->post('id',"");
        $status = Yii::$app->request->post('status',"0");
        $model = ConfigPageManage::findOne($id);
        $model->status = $status;
        if ($model->save()) {
            $this->returnSuccess('','success');
        } else {
            $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
    }
    //删除全局分页 --修改is_del状态（0可用 1删除）
    public function actionDelete(){
        $id = Yii::$app->request->post('id',"");
        $exam = ConfigPageManage::find()->where(['is_del'=>0,'id'=>$id,'status'=>1])->one();
        $model = ConfigPageManage::findOne($id);
        $model->is_del = 1;
        if(!empty($exam)){
            $this->returnError('','该分页正在被使用不可删除');
        }else{
            if ($model->save()) {
                $this->returnSuccess('','success');
            } else {
                $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
            }
        }
    }

    //批量删除
    public function actionDeleteAll(){
        $id = Yii::$app->request->get('id',"");
        $array = explode(',',$id);
        unset($array[0]);
        if(!empty($array)){
            foreach ($array as $key => $value) {
                //判断有没有频道或者栏目在使用
                $isUser = ConfigPageManage::find()->where(['is_del'=>0,'id'=>$value,'status'=>1])->one();
                if(empty($isUser)) {
                    $model = ConfigPageManage::findOne($value);
                    $model->is_del = 1;
                    $model->save();
                }
            }   
            $this->returnSuccess('','success');
        }else{
            $this->returnError('','请选择要删除的数据');
        }
    }

     //即点即改分页数量
    public function actionUpdateNum(){
        $request = Yii::$app->request->get();
        $model = ConfigPageManage::findOne($request['id']);
        $model->num = $request['num'];
        if ($model->save()) {
            $this->returnSuccess('','success');
        } else {
            $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
    }
}
