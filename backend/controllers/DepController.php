<?php
/**
 * Created by PhpStorm.
 * User: spacing
 * Date: 2019/10/18
 * Time: 13:05
 */

namespace backend\controllers;

use common\models\AdminDep;
use common\models\AdminUnit;
use common\models\auth\AdminAuthRelation;
use common\models\auth\AuthItem;
use common\models\auth\AuthPermission;
use common\models\User;
use common\utils\ToolUtil;
use yii\helpers\ArrayHelper;

class DepController extends BaseController
{
    protected $except = ['dep-list','add-dep'];
    /**
     * @Function 获取部门数据
     * @Author 1042463605@qq.com
     */
    public function actionGetDepList(){
        $depId = $this->post('depId');
        $depName = $this->post('depName');
        $unitId =  $this->post('unitId');
        if(!empty($depId) && $depName  ==''){
            $query = AdminDep::find()->filterWhere(['AND',['is_del' => 0,'father_id'=>$depId]]);
        }elseif($depName !='' && $depId == ''){
            $query = AdminDep::find()->filterWhere(['AND',['is_del' => 0]])->andFilterWhere(['like','name',$depName]);
        }elseif($depName !='' && $depId !=''){
            $query = AdminDep::find()->filterWhere(['AND',['is_del' => 0,'father_id'=>$depId]])->andFilterWhere(['like','name',$depName]);
        }elseif(!empty($unitId) && !empty($depId)){
            $query = AdminDep::find()->filterWhere(['AND',['is_del' => 0,'unit_id'=>$unitId]]);
        }elseif(!empty($unitId)){
            $query = AdminDep::find()->filterWhere(['AND',['is_del' => 0,'unit_id'=>$unitId]]);
        }else{
            $query = AdminDep::find()->filterWhere(['AND',['is_del' => 0]]);
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
 * @Function 获取员工列表
 * @Author   1042463605@qq.com
 */
    public function actionGetMemberList(){
        $depId = $this->post('depId');
        $tableName = AdminAuthRelation::tableName();
        $qurey = AdminAuthRelation::find();
        if(!empty($depId)){
            $qurey->select(['*'])->leftJoin(User::tableName(),"adminid = user_id")
                ->where(['depid' => $depId, 'type' => AdminAuthRelation::TYPE_DEP]);
            $this->sidx = $tableName.'.created_at';
            $dealFunction = function ($lists){
                foreach ($lists as $key => $list){
                    $lists[$key]['created_at'] = ToolUtil::getDate($list['created_at'],"Y-m-d H:i:s");
                    $lists[$key]['updated_at'] = ToolUtil::getDate($list['updated_at'],"Y-m-d H:i:s");
                }
                return $lists;
            };
            return $this->getJqTableData($qurey,$dealFunction);
        }else{
            return $this->asJson([
                'code' => 0,
                'msg' => '',
                'count' =>0,
                'data' => [],
            ]);
        }
    }
    /**
     * @Function 部门列表
     * @Author 1042463605@qq.com
     */
    public function actionDepList(){
        $isSuper = self::IsSuperAdmin();
        $unitId = '1009';
        $unitName = '全媒体云';
        $depId = $this->get('depId');
        $unit_Id = $this->get('unit_id');
        $depName = $this->get('depName');
        $memberList= $depList = $newDepList= [];
        if(!empty($depId)){
            $memberList = User::findAllByWhere(['status'=>User::STATUS_ACTIVE,'dep_id'=>$depId],['user_id','username','real_name','phone','email','created_at','dep_isleader','team_leader'],'real_name desc');
        }
       if(\Yii::$app->request->isPost){
            $userId = $this->post('user_id');
            $return = (!empty($userId)) ? ToolUtil::returnAjaxMsg(true,'更新成功') : ToolUtil::returnAjaxMsg(true,'添加成功');
            $userModel = new User();
            $addRes = $userModel -> adminAdd($this->post(),$userId);
            if(empty($addRes)){
                $return = ToolUtil::returnAjaxMsg(false,$userModel->getModelError());
            }
            return $this->asJson($return);
            exit;
        }
        return $this->render('deplist',['isSuper'=>$isSuper,'depName'=>$depName,'depId'=>$depId,'unit_id'=>$unit_Id,'memberList'=>$memberList]);
    }
    /**
     * @Function 根据单位id获取下面的部门列表
     * @Author 1042463605@qq.com
     */
    public function actionGetDepListByUnitId(){
        $unit_Id = $this->unit_id;
        $isSuper = self::IsSuperAdmin();
        $unitId  = $this->post('unitid');
        $depId  = $this->post('depid');
        if($isSuper){
            $depList = AdminUnit::findAllByWhere(['u_status'=>AdminUnit::STATUS_ACTIVE,'is_del'=>0],['unitid','name','auth_item_id'],'unitid desc');
        }else{
            $depList = AdminUnit::findAllByWhere(['u_status'=>AdminUnit::STATUS_ACTIVE,'is_del'=>0,'unit_id'=>$unit_Id],['unitid','name','auth_item_id'],'name desc');
        }
        if($unitId){
             $depList = AdminDep::findAllByWhere(['d_status'=>AdminDep::STATUS_ACTIVE,'is_del'=>0,'unit_id'=>$unitId],['depid','name','auth_item_id'],'depid desc');
        }
        if($depId){
            $depList = AdminDep::findAllByWhere(['d_status'=>AdminDep::STATUS_ACTIVE,'is_del'=>0,'father_id'=>$depId],['depid','name','auth_item_id'],'depid desc');
        }
       if($depList){
           foreach($depList as $value){
               $value['isParent'] = false;
               if($depId){
                   $isHasChild = AdminDep::findAllByWhere(['d_status'=>AdminDep::STATUS_ACTIVE,'is_del'=>0,'father_id'=>$value['depid']],['depid'],'depid desc');
                   if($isHasChild){
                       $value['isParent'] = true;
                   }
               }elseif($unitId){
                   $isHasChild = AdminDep::findAllByWhere(['d_status'=>AdminDep::STATUS_ACTIVE,'is_del'=>0,'father_id'=>$value['depid']],['depid'],'depid desc');
                   if($isHasChild){
                       $value['isParent'] = true;
                   }
               }else{
                   $isHasChild = AdminDep::findAllByWhere(['d_status'=>AdminDep::STATUS_ACTIVE,'is_del'=>0,'unit_id'=>$value['unitid']],['depid'],'depid desc');
                   if($isHasChild){
                       $value['isParent'] = true;
                   }
               }
               $newUnitList[] = $value;
           }
       }
       return json_encode($newUnitList);exit;
    }
    /**
     * @Function 设置用户为leader
     * @Author 1042463605@qq.com
     */
    public function actionSetLeader(){
        $userId = $this->post('userId');
        $type = $this->post('type');
        $isLeader = ($type=='set') ? 1 : 0;
        $updateRes = User::updateAll(['dep_isleader' => $isLeader]," user_id = :user_id",[":user_id" => $userId]);
        $return = ($updateRes) ? ToolUtil::returnAjaxMsg(true,'设置成功') : ToolUtil::returnAjaxMsg(false,'设置失败');
        return $this->asJson($return);exit;
    }
    /**
     * @Function 添加部门页面
     * @Author 1042463605@qq.com
     * @return string
     */
    public function actionAddDep(){
        $ids = $this->get('depId');
        if($ids){
            $depInfo = AdminDep::findValueByWhere(['depid' => $ids],[],['depid' => SORT_DESC]);
            return $this->render('_depadd',['depInfo' => $depInfo,'depId'=>$ids]);
        }else{
            return $this->render('_depadd');
        }
    }

    /**
     * @Function 删除部门
     * @Author 1042463605@qq.com
     */
    public function actionDelDep(){
        $ids = $this->post('ids');
        return $this->asJson(AdminDep::delDep($ids));
    }

    /**
     * @Function 修改部门状态
     * @Author 1042463605@qq.com
     * @return \yii\web\Response
     */
    public function actionUpdateDepStatus(){
        $ids = $this->post('ids');
        $checked = $this->post('checked');
        $status = (!empty($checked)) ? AdminDep::STATUS_ACTIVE: AdminDep::STATUS_INACTIVE;
        $updateRes = AdminDep::updateAll(['d_status' => $status]," depid = :depid",[":depid" => $ids]);
        $statusText = ($checked == true) ? '开启成功' : '停用成功';
        $return = ($updateRes) ? ToolUtil::returnAjaxMsg(true,$statusText) : ToolUtil::returnAjaxMsg(false,'操作失败');
        return $this->asJson($return);
    }
    /**
     * @Function 修改部门信息
     * @Author 1042463605@qq.com
     * @return \yii\web\Response
     */
    public function actionDepEdit(){
        $depId = $this->get('depId');
        $unit_id = $this->get('unit_id');
        if(\Yii::$app->request->isGet){
            return $this->render('_depadd',['depInfo' =>[],'depId'=>$depId,'unit_id'=>$unit_id]);
            exit;
        }
        $postData = $this->post();
        if($postData){
            if(isset($postData['dep']['depid']) && $postData['dep']['depid'] ==''){
                $dep = new  AdminDep();
                $dep->name = $postData['dep']['name'];
                $dep->description = $postData['dep']['description'];
                if($postData['depId'] !=''){
                    $dep->father_id = $postData['depId'];
                }else{
                    $dep->father_id = 1;
                }
                $dep->unit_id = $postData['unit_id'];
                $dep->siteid = 1;
                $dep->created_at = time();
                $dep->updated_at = time();
                $dep->d_status = AdminDep::STATUS_ACTIVE;
                $dep->is_del = 0;
                //var_dump($dep->getModelError());
                if($dep->save()){
                    $updateRes = AdminDep::updateAll(['auth_item_id' => 'Dep_'.$dep->attributes['depid'],'updated_at' => time()], " depid = :depid", [":depid" => $dep->attributes['depid']]);
                    if($updateRes ){
                        return $this->asJson(AuthItem::createAuthItem([
                            'name' =>'Dep_'.$dep->attributes['depid'],
                            'description' => $postData['dep']['name']
                        ],AuthPermission::TYPE_DEP));
//                         $return = ToolUtil::returnAjaxMsg(true,'新增成功');
                    }else{
                         $return = ToolUtil::returnAjaxMsg(true,'操作失败');
                    }
                }else{
                    $return = ToolUtil::returnAjaxMsg(true,'操作失败');
                }
            }else {
                $updateRes = AdminDep::updateAll(['name' => $postData['dep']['name'], 'description' => $postData['dep']['description'], 'updated_at' => time()], " depid = :depid", [":depid" => $postData['dep']['depid']]);
                if ($updateRes) {
                    $return = ToolUtil::returnAjaxMsg(true, '操作成功');
                } else {
                    $return = ToolUtil::returnAjaxMsg(false, '操作失败');
                }
            }
        }else{
            $return = ToolUtil::returnAjaxMsg(false,'操作失败');
        }
        return $this->asJson($return);
    }
    /**
     * @Function 修改角色状态
     * @Author 1042463605@qq.com
     */
    public function actionUpdateAuthStatus(){
        $name = $this->post('name');
        $checked = $this->post('checked');
        $status = (!empty($checked)) ? AuthItem::STATUS_ACTIVE: AuthItem::STATUS_INACTIVE;
        $updateRes = AuthItem::updateAll(['status' => $status]," name = :name",[":name" => $name]);
        $statusText = ($checked == true) ? '开启成功' : '禁用成功';
        $return = ($updateRes) ? ToolUtil::returnAjaxMsg(true,$statusText) : ToolUtil::returnAjaxMsg(false,'操作失败');
        return $this->asJson($return);
    }
    /**
     * @Function 修改部门权限
     * @Author 1042463605@qq.com
     */
    public function actionDepAuth(){
        if(\Yii::$app->request->isPost){
            $postData['name']=$this->post('name');
            $return = (!empty($postData['name'])) ? ToolUtil::returnAjaxMsg(true,'更新成功') : ToolUtil::returnAjaxMsg(true,'添加成功');
            $postData['_csrfBackend']=$this->post('_csrfBackend');
            $postData['auth']['description']=$this->post('description');
            $postData['auth']['name']=$this->post('name');
            $postData['auth']['order_sort']=100;
            $postData['auth']['type']=AuthPermission::TYPE_DEP;
            $postData['auth']['roles']=rtrim($this->post('roles'),',');
            $authItem = new AuthItem();

            $roles = rtrim($this->post('roles'),',');
            $name = $this->post('name');
            $addRes =$authItem::addChild($name,$roles);
            if(empty($addRes['status'])){
                $return = $addRes;
            }
            return $this->asJson($return);
            exit;
        }
        $authId = $this->get('authId');
        $authManager = \Yii::$app->getAuthManager();
        $authIdPermission = ArrayHelper::toArray($authManager->getPermissionsByRole($authId));
        $hasPermission = ArrayHelper::toArray($authManager->getPermissionsByUser($this->user_id));
        $auth = [];
        if(!empty($hasPermission)){
            foreach($hasPermission as $v){
                $father_id = $v['parentName'] !='' ?  md5($v['parentName']) : md5(0);
                $checked = 0;
                if(in_array($v,$authIdPermission)){
                    $checked = 1;
                }
                $auth[] = array('depid'=>md5($v['name']),'father_id'=>$father_id,'name'=>$v['description'],'true_name'=>$v['name'],'checked'=>$checked);
            }
        }
        return $this->render('_depauth',['authId'=>$authId,'auth'=>json_encode($auth)]);
    }

    /**
     * @Function 部门批量导入人员
     * @Author 1042463605@qq.com
     */
    public function actionBatchPort(){
        $t = '';
        $action = $this->get('type');
        $type = AdminAuthRelation::TYPE_DEP;
        $depId = $this->get('depId');
        $authName = AdminDep::findValueByWhere(['depid'=>$depId],'auth_item_id',[]);
        if($action == 'batchExport'){
           $t = 'out';
        }
        return $this->render('/auth/_adminlist',['depId'=>$depId,'type'=>$type, 't'=>$t, 'action'=>$action,'authName'=>$authName,'id'=>$depId]);
    }
}