<?php
/**
 * Created by PhpStorm.
 * User: WANGWEIHUA
 * Date: 2019/8/4
 * Time: 17:05
 */

namespace backend\controllers;


use common\models\AdminUnit;
use common\models\auth\AdminAuthRelation;
use common\models\auth\AuthItem;
use common\models\auth\AuthPermission;
use common\models\User;
use common\utils\ToolUtil;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class AuthController extends BaseController
{
    protected $except = ['auth/select-admin'];

    /**
     * @Function 菜单视图
     * @Author Weihuaadmin@163.com
     */
    public function actionMenuList(){
        if(\Yii::$app->request->isPost) {
            $name = $this->post('name');
            $return = (!empty($name)) ? ToolUtil::returnAjaxMsg(true,'更新成功') : ToolUtil::returnAjaxMsg(true,'添加成功');
            $authItem = new AuthItem();
            $addRes = $authItem -> addMenu($this->post(),$name);
            if(empty($addRes['status'])){
                $return = $addRes;
            }
            return $this->asJson($return);
        }
        return $this->render('menulist');
    }

    /**
     * @Function 删除菜单
     * @Author Weihuaadmin@163.com
     * @return \yii\web\Response
     */
    public function actionDelMenu(){
        $name = $this->post('ids');
        return $this->asJson(AuthItem::delMenu($name));
    }

    /**
     * @Function 添加菜单
     * @Author Weihuaadmin@163.com
     */
    public function actionAddMenu(){
        $name = $this->get('name');
        if($name){
            $model = AuthItem::findValueByWhere(['name' => $name],null,['name' => SORT_DESC]);
            if(empty($model['parent_name'])){
                $pModel = ['name' => null, 'description' => '顶级菜单'];
            }else{
                $pModel = AuthItem::findValueByWhere(['name'=>$model['parent_name']],['parent_name','name','description'],['name' => SORT_DESC]);
            }
            return $this->render('_menuadd',[
                'model' => $model,
                'pModel' => $pModel,
            ]);
        }else{
            $parentName = $this->get('pName');
            $pModel = AuthItem::findValueByWhere(['name' => $parentName],null,['name' => SORT_DESC]);
            $model = new AuthItem();
            return $this->render('_menuadd',[
                'model' => $model,
                'pModel' => $pModel
            ]);
        }
    }

    /**
     * @Function 获取菜单数据
     * @Author: Weihuaadmin@163.com
     */
    public function actionGetMenuList(){
        $this->sidx = 'order_sort';
        $this->sord = 'DESC';
        $authModel = \Yii::$app->getAuthManager();
        $lists = $authModel->getPermissionsByUser($this->user_id);
        $data = [];
        $key = 0;
        foreach ($lists as $list){
            $data[$key]['authority'] = $list->name;
            $data[$key]['authorityId'] = $list->name;
            $data[$key]['authorityName'] = $list->description;
            $data[$key]['checked'] = 0;
            $data[$key]['createTime'] = ToolUtil::getDate($list->createdAt,"Y-m-d H:i:s");
            $data[$key]['menuIcon'] = '';
            $data[$key]['menuUrl'] = '';
            $data[$key]['isMenu'] = $list->isMenu;
            $data[$key]['orderNumber'] = $list->orderSort;
            $data[$key]['parentId'] = !empty($list->parentName) ? $list->parentName : 0;
            $data[$key]['updateTime'] = $list->updatedAt;
            $key++;
        }
        return $this->asJson([
            'code' => 0,
            'msg' => '',
            'count' => count($data),
            'data' => $data,
        ]);
    }

    /**
     * @Function 角色管理
     * @Author Weihuaadmin@163.com
     * @return string
     */
    public function actionAuthList(){
        if(\Yii::$app->request->isPost) {
            $name = $this->post('name');
            $return = (!empty($name)) ? ToolUtil::returnAjaxMsg(true,'更新成功') : ToolUtil::returnAjaxMsg(true,'添加成功');
            $authItem = new AuthItem();
            $addRes = $authItem -> addAuth($this->post(),$name);
            if(empty($addRes['status'])){
                $return = $addRes;
            }
            return $this->asJson($return);
        }
        return $this->render('authlist');
    }

    /**
     * @Function 获取角色管理数据
     * @Author Weihuaadmin@163.com
     */
    public function actionGetAuthList(){
        $authName = $this->post('authName');
        $start = $this->post('start');
        $end = $this->post('end');
        $query = AuthItem::find()->filterWhere(['AND',['type' => 1],['like','description',$authName]]);
        if(!empty($start)){
            $startTime = strtotime($start. " 00:00:00");
            $query->andWhere(['>=','created_at',$startTime]);
        }
        if(!empty($end)){
            $endTime = strtotime($end. " 23:59:59");
            $query->andWhere(['<=','created_at',$endTime]);
        }
        $this->sidx = 'order_sort DESC,created_at';
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
     * @Function 管理员列表
     * @Author Weihuaadmin@163.com
     */
    public function actionAdminList(){
        if(\Yii::$app->request->isPost){
            $userId = $this->post('user_id');
            $return = (!empty($userId)) ? ToolUtil::returnAjaxMsg(true,'更新成功') : ToolUtil::returnAjaxMsg(true,'添加成功');
            $userModel = new User();
            $superRole = \Yii::$app->getAuthManager()->superRole;
            $postData = $this->post();
            if($postData['user']['real_name'] == $superRole || $postData['user']['username'] == $superRole){
                return ToolUtil::returnAjaxMsg(false,'登录名或者真实姓名不能使用admin！！');
            }
            $addRes = $userModel -> adminAdd($postData,$userId);
            if(empty($addRes)){
                $return = ToolUtil::returnAjaxMsg(false,$userModel->getModelError());
            }
            return $return;
        }
        return $this->render('adminlist');
    }

    /**
     * @Function 添加管理员页面
     * @Author Weihuaadmin@163.com
     * @return string
     */
    public function actionAddAdmin(){
        $userId = $this->get('userId');
        $roles = AuthItem::getAuthItemByType(AuthPermission::TYPE_ROLE);
        if(AuthItem::IsSuperAdmin()){
            $unitList = AdminUnit::findAllByWhere(['u_status'=>AdminUnit::STATUS_ACTIVE,'is_del' => AdminUnit::STATUS_NO_DELETED],'',['unitid'=>SORT_DESC]);
            $unitList = ArrayHelper::map($unitList,'unitid','name');
        }else{
            $unitList = AdminUnit::findAllByWhere(['unitid' => $this->unit_id],['name','unitid'],['unitid' => SORT_DESC]);
            $unitList = ArrayHelper::map($unitList,'unitid','name');
        }

        if($userId){
            $userInfo = User::findValueByWhere(['user_id' => $userId],[],['user_id' => SORT_DESC]);
            $hasRoles = explode(',',$userInfo['role_id']);
            $unitId = AdminAuthRelation::findValueByWhere(['adminid' => $userId],'unitid');
            return $this->render('_adminadd',[
                'userInfo' => $userInfo,
                'roles' => $roles,
                'hasRoles' => $hasRoles,
                'unitList' => $unitList,
                'unitId' => $unitId
            ]);
        }else{
            $userInfo = new User();
            return $this->render('_adminadd',[
                'userInfo' => $userInfo,
                'roles' => $roles,
                'unitList' => $unitList,
                'unitId' => ''
            ]);
        }
    }

    /**
     * @Function 获取Data
     * @Author Weihuaadmin@163.com
     * @return array
     */
    public function actionGetAdminList(){
        $bindPhone = $this->post('bindPhone');
        $start = $this->post('start');
        $end = $this->post('end');
        $query = User::find()
            ->filterWhere(['AND',['like','phone',$bindPhone]])
            ->andWhere(['AND',['in','status',[User::STATUS_ACTIVE,User::STATUS_INACTIVE]]]);
        if(!empty($start)){
            $startTime = strtotime($start. " 00:00:00");
            $query->andWhere(['>=','created_at',$startTime]);
        }
        if(!empty($end)){
            $endTime = strtotime($end. " 23:59:59");
            $query->andWhere(['<=','created_at',$endTime]);
        }

        $this->sidx = 'user_id';
        $dealFunction = function ($lists){
            $adminManager = \Yii::$app->authManager;
            foreach ($lists as $key => $list){
                $authModel = $adminManager->getRolesByUser($list['user_id']);
                $roleName = ArrayHelper::getColumn($authModel,'description');
                $roleName = implode('、',$roleName);
                $unitid = AdminAuthRelation::findValueByWhere(['adminid' => $list['user_id']],'unitid');
                $lists[$key]['unit'] = AdminUnit::findValueByWhere(['unitid' => $unitid],'name',['unitid'=>SORT_DESC]);

                $lists[$key]['roleName'] = $roleName;
                $lists[$key]['created_at'] = ToolUtil::getDate($list['created_at'],"Y-m-d H:i:s");
            }
            return $lists;
        };
        return $this->getJqTableData($query,$dealFunction);
    }

    /**
     * @Function 删除管理员
     * @Author Weihuaadmin@163.com
     */
    public function actionDelAdmin(){
        $ids = $this->post('ids');
        return $this->asJson(User::delAdmin($ids));
    }

    /**
     * @Function 修改管理员状态
     * @Author Weihuaadmin@163.com
     * @return \yii\web\Response
     */
    public function actionUpdateAdminStatus(){
        $ids = $this->post('ids');
        $checked = $this->post('checked');
        $status = (!empty($checked)) ? User::STATUS_ACTIVE: User::STATUS_INACTIVE;
        $userName = User::findValueByWhere(['user_id'=>$ids],['real_name'],['user_id'=>SORT_ASC]);
        $superRole = \Yii::$app->getAuthManager()->superRole;
        if($userName == $superRole){
            return $this->asJson(ToolUtil::returnAjaxMsg(false,'超级管理员不能为禁用！！'));
        }
        $updateRes = User::updateAll(['status' => $status]," user_id = :user_id",[":user_id" => $ids]);
        $statusText = ($checked == true) ? '开启成功' : '停用成功';
        $return = ($updateRes) ? ToolUtil::returnAjaxMsg(true,$statusText) : ToolUtil::returnAjaxMsg(false,'操作失败');
        return $this->asJson($return);
    }

    /**
     * @Function 修改角色状态
     * @Author Weihuaadmin@163.com
     */
    public function actionUpdateAuthStatus(){
        $name = $this->post('name');
        $checked = $this->post('checked');
        $status = (!empty($checked)) ? AuthItem::STATUS_ACTIVE: AuthItem::STATUS_INACTIVE;
        $superRole = \Yii::$app->getAuthManager()->superRole;
        if($name == $superRole){
            return $this->asJson(ToolUtil::returnAjaxMsg(false,'超级管理员不能为禁用！！'));
        }
        $updateRes = AuthItem::updateAll(['status' => $status]," name = :name",[":name" => $name]);
        $statusText = ($checked == true) ? '开启成功' : '禁用成功';
        $return = ($updateRes) ? ToolUtil::returnAjaxMsg(true,$statusText) : ToolUtil::returnAjaxMsg(false,'操作失败');
        return $this->asJson($return);
    }

    /**
     * @Function 删除角色
     * @Author Weihuaadmin@163.com
     */
    public function actionDelAuth(){
        $name = $this->post('name');
        return $this->asJson(AuthItem::delAuth($name));
    }

    /**
     * @Function 添加角色页面
     * @Author Weihuaadmin@163.com
     * @return string
     */
    public function actionAddAuth(){
        $name = $this->get('name');
        $authManager = \Yii::$app->getAuthManager();
        $hasPermission = $authManager->getPermissionsByRole($name);
        $hasPermission = array_keys($hasPermission);
        $permissions = $authManager->getPermissionsByUser($this->user_id);
        $permissions = ArrayHelper::toArray($permissions);
        $treeData = [];
        foreach ($permissions as $permission){
            $data['checked'] = AuthItem::getItemCheckedByLayuiTree($permission,$permissions,$hasPermission);
            $data['title'] = $permission['description'];
            $data['title'] = $permission['description'];
            $data['id'] = $permission['name'];
            $data['parentName'] = $permission['parentName'];
            $data['name'] = $permission['name'];
            $data['isMenu'] = 1;
            $treeData[] = $data;
        }
        $permissions = Json::encode(ToolUtil::arrToTree($treeData,null));
        if($name){
            $model = AuthItem::findValueByWhere(['name' => $name],[],['name' => SORT_DESC]);
            return $this->render('_authadd',[
                'model' => $model,
                'permissions' => $permissions,
                'hasPermission' => Json::encode($hasPermission)
            ]);
        }else{
            $model = new AuthItem();
            return $this->render('_authadd',[
                'model' => $model,
                'permissions' => $permissions
            ]);
        }
    }

    /**
     * @Function 批量移入/移除人员
     * @Author Weihuaadmin@163.com
     * @return string
     */
    public function actionSelectAdmin(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $type = $this->post('type');
            $id = $this->post('id');
            $userIds = $this->post('userId');
            $t = $this->post('t');
            if($t){
                if(AuthItem::delRoleByUserId($userIds,$id,$type)){
                    return ToolUtil::returnAjaxMsg(true,'操作成功');
                }
                return ToolUtil::returnAjaxMsg(false,'操作失败');
            }else{
                if(AuthItem::addRoleByUserId($userIds,$id,$type)){
                    return ToolUtil::returnAjaxMsg(true,'操作成功');
                }
                return ToolUtil::returnAjaxMsg(true,'操作失败');
            }
        }
        $type = $this->get('type'); //查询类型
        $id = $this->get('id'); //标识
        $t = $this->get('t'); //移入或者移除
        return $this->render('_adminlist',[
            'type' => $type,
            'id' => $id,
            't' => $t
        ]);
    }

    /**
     * @Function 获取数据
     * @Author Weihuaadmin@163.com
     */
    public function actionGetSelectAdmin(){
        $type = $this->post('type'); //查询类型
        $id = $this->post('id'); //标识
        $t = $this->post('t'); //标识
        $this->sidx = 'created_at';
        $this->sord = ' DESC';
        $query = User::find()->select(['user_id','username','real_name','phone','created_at'])
            ->filterWhere(['AND',['status' => User::STATUS_ACTIVE],['!=','real_name','admin']]);
        if($t){
            //移出
            if($type >= 0){
                $key = ToolUtil::getSelectType(AdminAuthRelation::getKey(),$type);
                $adminIds = AdminAuthRelation::findAllByWhere([$key => $id],'adminid');
                $adminIds = ArrayHelper::getColumn($adminIds,'adminid');
                $query->andWhere(['user_id' => $adminIds]);
            }else{
                $query->andWhere(['like','role_id',$id]);
            }
        }else{
            //移入
            if($type >= 0){
                $key = ToolUtil::getSelectType(AdminAuthRelation::getKey(),$type);
                $adminIds = AdminAuthRelation::findAllByWhere([$key => $id],'adminid');
                $adminIds = ArrayHelper::getColumn($adminIds,'adminid');
                $query->andWhere(['not in','user_id',$adminIds]);
            }else{
                $query->andWhere(['not like','role_id',$id]);
            }
        }
        $dealFuntion = function ($lists){
            $adminManager = \Yii::$app->authManager;
            foreach ($lists as $key => $list){
                $authModel = $adminManager->getRolesByUser($list['user_id']);
                $roleName = ArrayHelper::getColumn($authModel,'description');
                $roleName = implode('、',$roleName);
                $lists[$key]['created_at'] = ToolUtil::getDate($list['created_at'],'Y-m-d H:i:s');
                $lists[$key]['roleName'] = $roleName;
            }
            return $lists;
        };
        return $this->getJqTableData($query,$dealFuntion);
    }

    /**
     * @Function 修改菜单标识
     * @Author Weihuaadmin@163.com
     */
    public function actionUpdateMenu(){
        $menuId = $this->post('ids');
        $newName = $this->post('name');
        $menuInfo = AuthItem::findOne($menuId);
        if($menuInfo){
            $updateRes = AuthItem::updateAll(['updated_at' => time(), 'name' => $newName],"name =:name",[":name"=>$menuId]);
            if($updateRes){
                return ToolUtil::returnAjaxMsg(true,'操作成功');
            }
        }
        return ToolUtil::returnAjaxMsg(false,'操作失败');

    }
}