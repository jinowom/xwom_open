<?php
/**
 * Class name  is ConfigVariableController * @package backend\modules\common\controllers;
 * @author  whaibo  email:1316177158@qq.com
 * @DateTime 2020/12/10 10:13
 */
namespace backend\modules\common\controllers;

use Yii;
use common\models\config\ConfigVariable;
use backend\controllers\BaseController;
class ConfigVariableController extends BaseController{
    //设置全局变量首页
    public function actionIndex(){
        if (Yii::$app->request->isAjax) {
            $this->getConfigVariableLest();
        }else{
            return $this->render('index');
        }
    }
    //获取全局变量列表
    public function getConfigVariableLest(){
        $this->pageSize = Yii::$app->request->get('limit',5);
        $this->page = Yii::$app->request->get('page',1);
        $parames = Yii::$app->request->get('parames',"");
        $query = ConfigVariable::getVariableList($parames);
        $this->sidx = 'created_at';
        return $this->getJqTableData($query,"");
    }
    //添加全局变量
    public function actionCreate(){
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->post();
            $res = ConfigVariable::createDo($request);
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
            $res = ConfigVariable::updateDo($request);
            if($res === true){
                $this->returnSuccess('','success');
            }else{
                $this->returnError('',$res);
            }
        }else{
            $id = Yii::$app->request->get('id',"");
            $model = ConfigVariable::findOne($id);
            return $this->render('update',['data'=>$model]);
        }
    }
    //修改使用状态
    public function actionUpdateStatus(){
        $id = Yii::$app->request->post('id',"");
        $status = Yii::$app->request->post('status',"0");
        $model = ConfigVariable::findOne($id);
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
        $exam = ConfigVariable::find()->where(['is_del'=>0,'id'=>$id,'status'=>1])->one();
        $model = ConfigVariable::findOne($id);
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
    //批量删除
    public function actionDeleteAll(){
        $id = Yii::$app->request->get('id',"");
        $array = explode(',',$id);
        unset($array[0]);
        if(!empty($array)){
            foreach ($array as $key => $value) {
                //判断有没有频道或者栏目在使用
                $isUser = ConfigVariable::find()->where(['is_del'=>0,'id'=>$value,'status'=>1])->one();
                if(empty($isUser)) {
                    $model = ConfigVariable::findOne($value);
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
