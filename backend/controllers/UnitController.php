<?php
/**
 * Created by PhpStorm.
 * User: spacing
 * Date: 2019/10/20
 * Time: 17:05
 */

namespace backend\controllers;


use common\models\AdminUnit;
use common\models\auth\AuthItem;
use common\models\User;
use common\utils\ToolUtil;
use yii\helpers\ArrayHelper;

class UnitController extends BaseController
{
    protected $except = ['unit-list','add-unit'];

    /**
     * @Function 单位管理
     * @Author 1042463605@qq.com
     * @return string
     */
    public function actionUnitList(){
        $isSuper = self::IsSuperAdmin($this->user_id);
        $unitName = $this->get('unitName');

        return $this->render('unitlist',['unitName'=>$unitName,'isSuper'=>$isSuper]);
    }

    /**
     * @Function 获取单位管理数据
     * @Author 1042463605@qq.com
     */
    public function actionGetUnitList(){
        $unitName = $this->post('unitName');
        $isSuper = self::IsSuperAdmin($this->user_id);
        if($isSuper){
            if($unitName !=''){
                $query = AdminUnit::find()->filterWhere(['AND',['is_del' => 0]])->andFilterWhere(['like','name',$unitName]);
            }else{
                $query = AdminUnit::find()->filterWhere(['AND',['is_del' => 0]]);
            }
        }else{
             echo '根据所在的读取';exit;
        }

        $this->sidx = 'created_at';
        $dealFunction = function ($lists){
            foreach ($lists as $key => $list){
                $lists[$key]['created_at'] = ToolUtil::getDate($list['created_at'],"Y-m-d H:i:s");
                $lists[$key]['updated_at'] = ToolUtil::getDate($list['updated_at'],"Y-m-d H:i:s");
            }
            return $lists;
        };
        return $this->getJqTableData($query,$dealFunction);
    }


    /**
     * @Function 添加单位页面
     * @Author 1042463605@qq.com
     * @return string
     */
    /*public function actionAddUnit(){
        $userId = $this->get('userId');
        if($userId){
            $userInfo = User::findValueByWhere(['user_id' => $userId],[],['user_id' => SORT_DESC]);
            return $this->render('_adminadd',['userInfo' => $userInfo]);
        }else{
            return $this->render('_adminadd');
        }
    }*/

    /**
     * @Function 获取Data
     * @Author 1042463605@qq.com
     * @return array
     */
    /*public function actionGetUnitList(){
        $query = AdminUnit::find()->where(['AND',['in','status',[User::STATUS_ACTIVE,User::STATUS_INACTIVE]]]);
        $this->sidx = 'user_id';
        $dealFunction = function ($lists){
            $adminManager = \Yii::$app->authManager;
            foreach ($lists as $key => $list){
                $authModel = $adminManager->getRolesByUser($list['user_id']);
                $roleName = implode('|',array_keys($authModel));
                $lists[$key]['roleName'] = $roleName;
                $lists[$key]['created_at'] = ToolUtil::getDate($list['created_at'],"Y-m-d H:i:s");
            }
            return $lists;
        };
        return $this->getJqTableData($query,$dealFunction);
    }*/

    /**
     * @Function 删除单位
     * @Author 1042463605@qq.com
     */
    public function actionDelUint(){
        $ids = $this->post('ids');
        return $this->asJson(AdminUnit::delAdmin($ids));
    }

    /**
     * @Function 修改单位状态
     * @Author 1042463605@qq.com
     * @return \yii\web\Response
     */
    public function actionUpdateUnitStatus(){
        $ids = $this->post('ids');
        $checked = $this->post('checked');
        $status = (!empty($checked)) ? AdminUnit::STATUS_ACTIVE: AdminUnit::STATUS_INACTIVE;
        $updateRes = AdminUnit::updateAll(['u_status' => $status]," unitid = :unitid",[":unitid" => $ids]);
        $statusText = ($checked == true) ? '开启成功' : '停用成功';
        $return = ($updateRes) ? ToolUtil::returnAjaxMsg(true,$statusText) : ToolUtil::returnAjaxMsg(false,'操作失败');
        return $this->asJson($return);
    }
    /**
     * @Function 修改单位信息
     * @Author 1042463605@qq.com
     * @return \yii\web\Response
     */
    public function actionUnitEdit(){
         if(\Yii::$app->request->isGet){
             return $this->render('_unitadd',['unitInfo' =>[]]);
             exit;
         }
        $postData = $this->post();
        if($postData){
            if(isset($postData['unit']['unitid']) && $postData['unit']['unitid'] ==''){
                $unit = new  AdminUnit();
                $unit->name = $postData['unit']['name'];
                $unit->description = $postData['unit']['description'];
                $unit->u_status = AdminUnit::STATUS_ACTIVE;
                $unit->siteid = 0;
                $unit->sort_id = 0;
                $unit->app_id = 0;
                $unit->created_at = time();
                $unit->updated_at = time();
                $unit->is_del = 0;
                if($unit->save()){
                    $updateRes = AdminUnit::updateAll(['auth_item_id' => 'Unit_'.$unit->attributes['unitid'],'updated_at' => time()], " unitid = :unitid", [":unitid" => $unit->attributes['unitid']]);
                    if($updateRes ){
                        $return = ToolUtil::returnAjaxMsg(true,'新增成功');
                    }else{
                        $return = ToolUtil::returnAjaxMsg(true,'操作失败');
                    }
                }else{
                    $return = ToolUtil::returnAjaxMsg(true,'操纵失败');
                }
            }else{
                $Res = AdminUnit::updateAll(['name' => $postData['unit']['name'],'description'=> $postData['unit']['description'],'updated_at'=>time()]," unitid = :unitid",[":unitid" => $postData['unit']['unitid']]);
                if($Res){
                    $return = ToolUtil::returnAjaxMsg(true,'编辑成功');
                }else{
                    $return = ToolUtil::returnAjaxMsg(false,'操作失败');
                }
            }
        }else{
            $return = ToolUtil::returnAjaxMsg(false,'操作失败');
        }
        return $this->asJson($return);
    }
    /**
     * @Function 删除单位
     * @Author 1042463605@qq.com
     */
    public function actionDelUnit(){
        $ids = $this->post('ids');
        return $this->asJson(AdminUnit::delUnit($ids));
    }

    /**
     * @Function 添加角色页面
     * @Author 1042463605@qq.com
     * @return string
     */
    public function actionAddUnit(){
        $ids = $this->get('ids');
        if($ids){
            $unitInfo = AdminUnit::findValueByWhere(['unitid' => $ids],[],['unitid' => SORT_DESC]);
            return $this->render('_unitadd',['unitInfo' => $unitInfo]);
        }else{
            return $this->render('_unitadd');
        }
    }
}