<?php
/**
 * Created by PhpStorm.
 * User:spacing
 * Date: 2019/10/19
 * Time: 12:05
 */

namespace backend\controllers;


use common\models\AdminDep;
use common\models\AdminDepStatus;
use common\models\AdminTeam;
use common\models\AdminUnit;
use common\models\auth\AdminAuthRelation;
use common\models\auth\AuthItem;
use common\models\auth\AuthPermission;
use common\models\User;
use common\utils\ToolUtil;
use yii\helpers\ArrayHelper;

class TeamController extends BaseController
{
    protected $except = ['team-list','auth-list'];

    /**
     * @Function 管理员列表
     * @Author 1042463605@qq.com
     */
    public function actionTeamList(){
        $isSuper = self::IsSuperAdmin();
        $unitId = '1010';
        $unitName = '单位x';
        $teamId = $this->get('teamId');
        $unit_Id = $this->get('unit_id');
        $teamName = $this->get('teamName');
        $memberList= $teamList = [];
        if(!empty($teamId)){
            $memberList = User::findAllByWhere(['status'=>User::STATUS_ACTIVE,'team_id'=>$teamId],['user_id','username','real_name','phone','email','created_at','dep_isleader','team_leader'],'real_name desc');
        }
        if(\Yii::$app->request->isPost){
            $userId = $this->post('user_id');
            $return = (!empty($userId)) ? ToolUtil::returnAjaxMsg(true,'更新成功') : ToolUtil::returnAjaxMsg(true,'添加成功');
            $userModel = new User();
            $addRes = $userModel -> teamAdd($this->post(),$userId);
            if(empty($addRes)){
                $return = ToolUtil::returnAjaxMsg(false,$userModel->getModelError());
            }
            return $this->asJson($return);
        }
        return $this->render('teamlist',['isSuper'=>$isSuper,'teamName'=>$teamName,'teamId'=>$teamId,'unit_id'=>$unit_Id,'memberList'=>$memberList]);
    }

    /**
     * @Function 添加管理员页面
     * @Author 1042463605@qq.com
     * @return string
     */
    public function actionAddTeam(){
        $ids = $this->get('teamId');
        if($ids){
            $teamInfo = AdminTeam::findValueByWhere(['teamid' => $ids],[],['teamid' => SORT_DESC]);
            return $this->render('_teamadd',['teamInfo' => $teamInfo,'teamId'=>$ids]);
        }else{
            return $this->render('_teamadd');
        }
    }

    /**
     * @Function 获取Data
     * @Author 1042463605@qq.com
     * @return array
     */
    public function actionGetTeamList(){
        $teamId = $this->post('teamId');
        $teamName = $this->post('teamName');
        $unitId =  $this->post('unitId');
        if(!empty($teamId) && $teamName ==''){
            $query = AdminTeam::find()->filterWhere(['AND',['is_del' => 0,'father_id'=>$teamId]]);
        }elseif($teamName !='' && $teamId == ''){
            $query = AdminTeam::find()->filterWhere(['AND',['is_del' => 0]])->andFilterWhere(['like','name',$teamName]);
        }elseif($teamName !='' && $teamId !=''){
            $query = AdminTeam::find()->filterWhere(['AND',['is_del' => 0,'father_id'=>$teamId]])->andFilterWhere(['like','name',$teamName]);
        }elseif(!empty($unitId) && !empty($teamId)){
            $query = AdminTeam::find()->filterWhere(['AND',['is_del' => 0,'unit_id'=>$unitId]]);
        }elseif(!empty($unitId)){
            $query = AdminTeam::find()->filterWhere(['AND',['is_del' => 0,'unit_id'=>$unitId]]);
        }else{
            $query = AdminTeam::find()->filterWhere(['AND',['is_del' => 0]]);
        }
        $this->sidx = 'created_at';
        $teamFunction = function ($lists){
            foreach ($lists as $key => $list){
                $lists[$key]['created_at'] = ToolUtil::getDate($list['created_at'],"Y-m-d H:i:s");
                $lists[$key]['updated_at'] = ToolUtil::getDate($list['updated_at'],"Y-m-d H:i:s");
            }
            return $lists;
        };
        return $this->getJqTableData($query,$teamFunction);
    }
    /**
     * @Function 根据单位id获取下面的部门列表
     * @Author 1042463605@qq.com
     */
    public function actionGetTeamListByUnitId(){
        $unit_Id = $this->unit_id;
        $isSuper = self::IsSuperAdmin();
        $unitId  = $this->post('unitid');
        $depId  = $this->post('teamid');
        if($isSuper){
            $teamList = AdminUnit::findAllByWhere(['u_status'=>AdminUnit::STATUS_ACTIVE,'is_del'=>0],['unitid','name','auth_item_id'],'unitid desc');
        }else{
            $teamList = AdminUnit::findAllByWhere(['u_status'=>AdminUnit::STATUS_ACTIVE,'is_del'=>0,'unit_id'=>$unit_Id],['unitid','name','auth_item_id'],'name desc');
        }
        if($unitId){
            $teamList = AdminTeam::findAllByWhere(['t_status'=>AdminTeam::STATUS_ACTIVE,'is_del'=>0,'unit_id'=>$unitId],['teamid','name','auth_item_id'],'teamid desc');
        }
        if($depId){
            $teamList = AdminTeam::findAllByWhere(['t_status'=>AdminTeam::STATUS_ACTIVE,'is_del'=>0,'father_id'=>$depId],['teamid','name','auth_item_id'],'teamid desc');
        }
        if($teamList){
            foreach($teamList as $value){
                $value['isParent'] = false;
                if($depId){
                    $isHasChild = AdminTeam::findAllByWhere(['t_status'=>AdminTeam::STATUS_ACTIVE,'is_del'=>0,'father_id'=>$value['teamid']],['teamid'],'teamid desc');
                    if($isHasChild){
                        $value['isParent'] = true;
                    }
                }elseif($unitId){
                    $isHasChild = AdminTeam::findAllByWhere(['t_status'=>AdminTeam::STATUS_ACTIVE,'is_del'=>0,'father_id'=>$value['teamid']],['teamid'],'teamid desc');
                    if($isHasChild){
                        $value['isParent'] = true;
                    }
                }else{
                    $isHasChild = AdminTeam::findAllByWhere(['t_status'=>AdminTeam::STATUS_ACTIVE,'is_del'=>0,'unit_id'=>$value['unitid']],['teamid'],'teamid desc');
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
     * @Function 获取员工列表
     * @Author 1042463605@qq.com
     */
    public function actionGetMemberList(){
        $teamId = $this->post('teamId');
        $tableName = AdminAuthRelation::tableName();
        $qurey = AdminAuthRelation::find();
        if(!empty($teamId)){
            $qurey->select(['*'])->leftJoin(User::tableName(),"adminid = user_id")
                ->where(['teamid' => $teamId, 'type' => AdminAuthRelation::TYPE_TEAM]);
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
     * @Function 删除管理员
     * @Author 1042463605@qq.com
     */
    public function actionDelTeam(){
        $ids = $this->post('ids');
        return $this->asJson(AdminTeam::delTeam($ids));
    }

    /**
     * @Function 修改管理员状态
     * @Author 1042463605@qq.com
     * @return \yii\web\Response
     */
    public function actionUpdateTeamStatus(){
        $ids = $this->post('ids');
        $checked = $this->post('checked');
        $status = (!empty($checked)) ? AdminTeam::STATUS_ACTIVE: AdminTeam::STATUS_INACTIVE;
        $updateTeam = AdminTeam::updateAll(['t_status' => $status]," teamid = :teamid",[":teamid" => $ids]);
        $statusText = ($checked == true) ? '开启成功' : '停用成功';
        $return = ($updateTeam) ? ToolUtil::returnAjaxMsg(true,$statusText) : ToolUtil::returnAjaxMsg(false,'操作失败');
        return $this->asJson($return);
    }
    /**
     * @Function 修改团队信息
     * @Author 1042463605@qq.com
     * @return \yii\web\Response
     */
    public function actionTeamEdit(){
        $teamId = $this->get('teamId');
        $unit_id = $this->get('unit_id');
        if(\Yii::$app->request->isGet){
            return $this->render('_teamadd',['teamInfo' =>[],'teamId'=>$teamId,'unit_id'=>$unit_id]);
            exit;
        }
        $postData = $this->post();
        if($postData){
            if(isset($postData['team']['teamid']) && $postData['team']['teamid'] ==''){
                $team = new  AdminTeam();
                $team->name = $postData['team']['name'];
                $team->description = $postData['team']['description'];
                $team->created_at = time();
                $team->updated_at = time();
                if($postData['teamId'] !=''){
                    $team->father_id = $postData['teamId'];
                }else{
                    $team->father_id = 1;
                }
                $team->unit_id = $postData['unit_id'];
                $team->t_status = AdminTeam::STATUS_ACTIVE;
                $team->is_del = 0;
                if($team->save()){
                    $updateRes = AdminTeam::updateAll(['auth_item_id' => 'Team_'.$team->attributes['teamid'],'updated_at' => time()], " teamid = :teamid", [":teamid" => $team->attributes['teamid']]);
                    if($updateRes ){
                        return $this->asJson(AuthItem::createAuthItem([
                            'name' =>'Team_'.$team->attributes['teamid'],
                            'description' => $postData['team']['name']
                        ],AuthPermission::TYPE_DEP));
//                         $return = ToolUtil::returnAjaxMsg(true,'新增成功');
                    }else{
                        $return = ToolUtil::returnAjaxMsg(true,'操作失败');
                    }
                }else{
                    $return = ToolUtil::returnAjaxMsg(true,'操纵失败');
                }
            }else {
                $updateRes = AdminTeam::updateAll(['name' => $postData['team']['name'], 'description' => $postData['team']['description'], 'updated_at' => time()], " teamid = :teamid", [":teamid" => $postData['team']['teamid']]);
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
     * @Function 设置用户为leader
     * @Author 1042463605@qq.com
     */
    public function actionSetLeader(){
        $userId = $this->post('userId');
        $type = $this->post('type');
        $isLeader = ($type=='set') ? 1 : 0;
        $updateRes = User::updateAll(['team_leader' => $isLeader]," user_id = :user_id",[":user_id" => $userId]);
        $return = ($updateRes) ? ToolUtil::returnAjaxMsg(true,'设置成功') : ToolUtil::returnAjaxMsg(false,'设置失败');
        return $this->asJson($return);exit;
    }
    /**
     * @Function 修改部门权限
     * @Author 1042463605@qq.com
     */
    public function actionTeamAuth(){
        if(\Yii::$app->request->isPost){
            $postData['name']=$this->post('name');
            $return = (!empty($postData['name'])) ? ToolUtil::returnAjaxMsg(true,'更新成功') : ToolUtil::returnAjaxMsg(true,'添加成功');
            $postData['_csrfBackend']=$this->post('_csrfBackend');
            $postData['auth']['description']=$this->post('description');
            $postData['auth']['name']=$this->post('name');
            $postData['auth']['order_sort']=100;
            $postData['auth']['type']=AuthPermission::TYPE_TEAM;
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
                $auth[] = array('teamid'=>md5($v['name']),'father_id'=>$father_id,'name'=>$v['description'],'true_name'=>$v['name'],'checked'=>$checked);
            }
        }
        return $this->render('_teamauth',['authId'=>$authId,'auth'=>json_encode($auth)]);
    }
    /**
     * @Function 团队批量导入人员
     * @Author 1042463605@qq.com
     */
    public function actionBatchPort(){
        $t = '';
        $teamId = $this->get('teamId');
        $action = $this->get('type');
        $type = AdminAuthRelation::TYPE_TEAM;
        $authName = AdminTeam::findValueByWhere(['teamid'=>$teamId],'auth_item_id',[]);
        if($action == 'batchExport'){
            $t = 'out';
        }
        return $this->render('/auth/_adminlist',['teamId'=>$teamId,'type'=>$type,'t'=>$t,'action'=>$action,'authName'=>$authName,'id'=>$teamId]);
    }
}