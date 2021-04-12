<?php

namespace common\models\auth;
use common\models\AdminApp;
use common\models\AdminDep;
use common\models\AdminTeam;
use common\models\AdminUnit;
use common\models\reg\RegSoftware;
use common\models\User;
use common\utils\LogUtil;
use common\utils\ToolUtil;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name 角色/权限名称
 * @property string $type 类型 1表示角色 2表示权限 3 单位 4 部门 5 团队 6 子系统 7 子站点
 * @property string $parent_name 父节点
 * @property string $description 说明
 * @property string $rule_name 权限规则，与规则表关联
 * @property string $data 自定义数据
 * @property int $channel_id 频道id
 * @property int $catid 栏目ID
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $status 状态  0禁用 1启用
 * @property int $order_sort 序号 默认100
 * @property string $icon 图标
 * @property int $is_menu 是否是菜单 0否 1是
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 */
class AuthItem extends \common\models\BaseModel
{
    const STATUS_ACTIVE = 1; //正常
    const STATUS_INACTIVE = 0; //禁用
    const SCENARIO_ADD = 'add';
    const SCENARIO_UPDATE = 'update';
    const DEP_TYPE = 3;
    const TEAM_TYPE = 4;

    //成员数量
    public $userCount;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    public static function getType()
    {
        //1表示角色 2表示权限 3 单位 4 部门 5 团队 6 子系统 7 子站点
        return ['1' => '角色', '2' => '权限', '3' => '单位', '4' => '部门', '5' => '团队', '6' => '子系统', '7'=> '子站点'];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios = ArrayHelper::merge($scenarios,[
            self::SCENARIO_ADD => ['name','type','created_at','updated_at','status','order_sort','is_menu','description','icon','parent_name'],
            self::SCENARIO_UPDATE => ['name','type','created_at','updated_at','status','order_sort','is_menu','description','icon','parent_name']
        ]);
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required','message' => '{attribute}不能为空'],
            [['type', 'channel_id', 'catid', 'created_at', 'updated_at', 'status', 'order_sort', 'is_menu'], 'integer'],
            [['description', 'data'], 'string','message' => '{attribute}不能为全数字'],
            ['order_sort','compare', 'compareValue' => 999, 'operator' => '<','message' => '{attribute}不能大于999'],
            [['name', 'parent_name', 'rule_name'], 'string', 'max' => 64,'message' => '{attribute}字符最大不能超过64个字符'],
            [['icon'], 'string', 'max' => 100, 'tooLong' => '{attribute}最多包含100个字符'],
            [['name'], 'unique','message' => '{attribute}已存在','on' => [self::SCENARIO_ADD]],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => '角色标识',
            'type' => '类型', // 1表示角色 2表示权限 3 单位 4 部门 5 团队 6 子系统 7 子站点
            'parent_name' => '父节点',
            'description' => '角色名称',
            'rule_name' => '权限规则，与规则表关联',
            'data' => '自定义数据',
            'channel_id' => 'Channel ID',
            'catid' => 'Catid',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'status' => '状态', //  0禁用 1启用
            'order_sort' => '序号',// 默认100
            'icon' => '图标',
            'is_menu' => '是否显示', //是菜单 0否 1是
            'userCount' => '成员数量'
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'   => function(){return time();},
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    /**
     * @Function 获取类型 1表示角色 2表示权限 3 单位 4 部门 5 团队 6 子系统 7 子站点
     * @Author Weihuaadmin@163.com
     * @return array
     */
    public function getTypeName()
    {
       return isset(self::getType()[$this->type]) ? self::getType()[$this->type] : "";
    }

    /**
     * @Function 成员数量
     * @Author Weihuaadmin@163.com
     * @return int|string
     */
    public function getUserCount(){
        return User::find()->where(['role_id' => $this->name])->count();
    }

    private static function _verifyExist($authName){
        return self::find()->where(['name' => $authName])->one();
    }

    /**
     * @Function 创建/更新标识 角色和部门、团队权限
     * @param $type type类型 参考  AuthPermission 常量
     * @param $operate 操作 默认 0 创建操作  1 更新操作
     * @Author Weihuaadmin@163.com
     */
    public static function createAuthItem(array $roleData,$type,$operate = 0){
        if(self::_verifyExist($roleData['name']) && empty($operate)){ //创建操作 判断是否已经存在标识
            return ToolUtil::returnMsg(false,'已经存在');
        }
        $authManager = \Yii::$app->authManager;
        $role = $authManager->createRole(null,$type);
        $role->name = isset($roleData['name']) ? $roleData['name'] : '';
        $role->description = isset($roleData['description']) ? $roleData['description'] : '';
        $role->parentName = isset($roleData['parent_name']) ? $roleData['parent_name'] : null;
        if(empty($operate)){
            $role->createdAt = time();
        }
        $role->updatedAt = time();
        $role->ruleName = isset($roleData['rule_name']) ? $roleData['rule_name'] : null;
        $role->data = null;
        $role->orderSort = isset($roleData['order_sort']) ? $roleData['order_sort'] : 100;
        $role->icon = isset($roleData['icon']) ? $roleData['icon'] : '';
        $role->status = 1;
        $role->isMenu = isset($roleData['is_menu']) ? $roleData['is_menu'] : 0;
        if(empty($operate)){
            if($authManager -> add($role)){
                return ToolUtil::returnMsg(true);
            }
        }else{
            if($authManager -> updateItem($roleData['name'],$role)){
                return ToolUtil::returnMsg(true);
            }
        }
        return ToolUtil::returnMsg(false,'操作失败');
    }

    /**
     * @Function 给角色或者团队、部门赋权
     * @param $authName  authItem 唯一标识
     * @param $power string 权限标识
     * @Author: Weihuaadmin@163.com
     */
    public static function addChild($authName,$power){

        $authManager = \Yii::$app->authManager;
        $role = self::_verifyExist($authName);
        if(empty($role) || empty($power)){
            return ToolUtil::returnMsg(false,'赋权的角色不存在或者权限不存在');
        }
        $powersArr = explode(',', $power);
        $permissions = [];
        /*foreach ($powersArr as $power) {
            $powerExist = $authManager->getPermission($power);
            if(empty($powerExist)){
                return ToolUtil::returnMsg(false,"权限<i class='x-red'>{$power}</i>的标识不存在!");
            }
            $permissions[] = $powerExist;
        }*/
        /*foreach ($permissions as $permission){
            $childExist = AuthItemChild::findValueByWhere(['parent'=> $role->name, 'child' => $permission->name],null,[]);
            if(empty($childExist)){
                $authManager->addChild($role, $permission);
            }
        }*/
        $authManager->removeChildren($role);
        foreach ($powersArr as $key => $permission){
            $powerExist = AuthItem::findValueByWhere(['name' => $permission],['name'],[]);
            $childExist = AuthItemChild::findValueByWhere(['parent'=> $role->name, 'child' => $permission],null,[]);
            if(empty($childExist) && !empty($powerExist)){
                $permissions[$key]['parent'] = $role->name;
                $permissions[$key]['child'] = $permission;
                unset($powerExist);
            }
        }
        $res = \Yii::$app->db->createCommand()->batchInsert($authManager->itemChildTable,['parent','child'],$permissions)->execute();
        if($res){
            return ToolUtil::returnMsg(true);
        }
        return ToolUtil::returnMsg(false);
    }

    /**
     * @Function 删除角色
     * @Author Weihuaadmin@163.com
     * @param $name 角色标识
     * @return array
     */
    public static function delAuth($name){
        $return = ToolUtil::returnAjaxMsg(false,'操作失败');
        $trans = \Yii::$app->db->beginTransaction();
        $authManager = \Yii::$app->getAuthManager();
        try {
            $role = $authManager->getRole($name);
            $roleArr = ArrayHelper::toArray($role);
            if(!isset($roleArr['name'])){
                throw new \Exception("角色不存在！！!");
            }
            $isExistUser = $authManager->getUserIdsByRole($name);
//            var_dump($isExistUser);exit;
            if(!empty($isExistUser)){
                throw new \Exception("该角色下还有管理员，不能删除！！!");
            }
            $res = $authManager->remove($role);
            if(!$res){
                throw new \Exception("移除权限!");
            };
            $trans->commit();
            $return = ToolUtil::returnAjaxMsg(true,'操作成功');
        } catch (\Exception $e) {
            $return['msg'] = $e->getMessage();
            $trans->rollback();
        }
        return $return;
    }

    /**
     * @Function 删除菜单
     * @param $name 菜单标识
     * @Author Weihuaadmin@163.com
     */
    public static function delMenu($name){
        try{
            $authManager = \Yii::$app->getAuthManager();
            $childen = self::findValueByWhere(['parent_name' => $name,"type" => AuthPermission::TYPE_PERMISSION],null,['order_sort'=>SORT_DESC]);
            if(!empty($childen)){
                throw new  \Exception("请先删除菜单下的子菜单！");
            }
            $authRes = $authManager->delAuth($name);
            if(empty($authRes)){
                throw new  \Exception("请先删除菜单下的子菜单！");
            }
            return ToolUtil::returnAjaxMsg(true,'操作成功');
        }catch (\Exception $e){
            return ToolUtil::returnAjaxMsg(false,$e->getMessage());
        }
    }

    /**
     * @Function 添加/更新菜单
     * @Author Weihuaadmin@163.com
     */
    public function addMenu($postData,$name){
        $postData['auth']['type'] = AuthPermission::TYPE_PERMISSION;
        $this->scenario = empty($name) ? self::SCENARIO_ADD : self::SCENARIO_UPDATE;
        $operate = empty($name) ? 0 : 1;
        if($this->load($postData,'auth') && $this->validate()){
            $roleData = $this->getAttributes();
            $return = self::createAuthItem($roleData,AuthPermission::TYPE_PERMISSION,$operate);
            if($return['status'] && empty($name)){
                //新加的菜单都需要给超级管理员添加
                $superRole = \Yii::$app->getAuthManager()->superRole;
                $authItemChildModel = new AuthItemChild();
                return $authItemChildModel->addChild([
                    'parent' => $superRole,
                    'child' => $roleData['name']
                ]);
            }
            return $return;
        }else{
            return ToolUtil::returnAjaxMsg(false,$this->getModelError());
        }
    }
    
    /**
     * @Function 获取角色标识通过类型
     * @param $type 关联 AuthPermission常量
     * @Author Weihuaadmin@163.com
     * @return mixed
     */
    public static function getAuthItemByType($type){
        return AuthItem::findAllByWhere(['type' => $type, 'status' => AuthItem::STATUS_ACTIVE],null,['order_sort' => SORT_DESC, 'created_at' => SORT_DESC]);
    }

    /**
     * @Function 添加/更新角色
     * @Author Weihuaadmin@163.com
     */
    public function addAuth($postData,$name){
        $postData['auth']['type'] = isset($postData['auth']['type']) ? $postData['auth']['type'] : AuthPermission::TYPE_ROLE;
        $this->scenario = empty($name) ? self::SCENARIO_ADD : self::SCENARIO_UPDATE;
        $operate = empty($name) ? 0 : 1;
        $roles = $postData['auth']['roles'];
        if($this->load($postData,'auth') && $this->validate()){
            $roleData = $this->getAttributes();
            $return = self::createAuthItem($roleData,AuthPermission::TYPE_ROLE,$operate);
            if($roles){
                $name = empty($name) ? $postData['auth']['name'] : $name;
                self::addChild($name,$roles);
            }
            return $return;
        }else{
            return ToolUtil::returnAjaxMsg(false,$this->getModelError());
        }
    }

    /**
     *  LayUITree获取节点选中状态 【只默认选中最后子节点 否认前端显示BUG】
     * @Author: Weihuaadmin@163.com
     */
    public static function getItemCheckedByLayuiTree($item,$items,$hasItems){
        //判断是否有子类
        $checked = true;
        foreach ($items as $it){
            if($it['parentName'] == $item['name']){
                $checked = false;
            }
        }
        return ($checked == true) ? ArrayHelper::isIn($item['name'],$hasItems) : $checked;
    }

    /* @Function 给角色批量添加用户
     * @Author Weihuaadmin@163.com
     * @param $userIds 用户ID
     * @param $role 角色标识
     * @param $type 角色类型
     */
    public static function addRoleByUserId($userIds,$role,$type = 1){
        $userIds = is_array($userIds) ? $userIds : explode(',',$userIds);
        $authManager = \Yii::$app->authManager;
        $relationModel = new AdminAuthRelation();
        foreach ($userIds as $key => $userId){
            $_authManager = clone $authManager;
            if($type > 2){
                $_relationModel = clone $relationModel;
                $data = self::_relationData($role,$type,$userId);
                if(!$_relationModel->createRow($data)){
                    return false;
                };
                $roleName = $data['auth_item_id'];
            }else{
                $role_id = User::findValueByWhere(['user_id' => $userId],'role_id',['user_id' => SORT_DESC]);
                $role_id = $role_id . ','.$role;
                User::updateAll(['role_id' => $role_id],'user_id = :user_id',[':user_id' => $userId]);
                $roleName = $role;
            }
            $authRoleName = $_authManager->getRole($roleName);
            $authManager->assign($authRoleName, $userId);
        }
        return true;
    }

    /**
     * @Function 封装插入数据
     * @Author Weihuaadmin@163.com
     * @param $role 权限相关数据
     * @param $type 权限类型
     * @param $userId
     * @return array
     */
    private static function _relationData($roleName,$type,$userId){
        $key = ToolUtil::getSelectType(AdminAuthRelation::getKey(),$type);
        //添加关系表
        $relationData = [
            'type' => $type,
            'adminid' => $userId,
        ];
        switch ($type){
            case AdminAuthRelation::TYPE_TEAM:
                $teamInfo = AdminTeam::findValueByWhere(['auth_item_id' => $roleName],['unit_id','teamid'],['teamid' => SORT_DESC]);
                $relationData['unit_id'] = $teamInfo['unit_id'];
                $relationData[$key] = $teamInfo['teamid'];
                break;
            case AdminAuthRelation::TYPE_DEP:
                $depInfo = AdminDep::findValueByWhere(['auth_item_id' => $roleName],['unit_id','depid'],['depid' => SORT_DESC]);
                $relationData['unit_id'] = $depInfo['unit_id'];
                $relationData[$key] = $depInfo['depid'];
                break;
            case AdminAuthRelation::TYPE_UNIT:
                $unitId = AdminUnit::findValueByWhere(['auth_item_id' => $roleName],['unit_id'],['unitid'=>SORT_DESC]);
                $relationData[$key] = $unitId;
                break;
            case AdminAuthRelation::TYPE_APP:
                $regId = RegSoftware::findValueByWhere(['route_map' => $roleName],['id']);
                $relationData[$key] = $regId;
                break;
        }
        $relationData['auth_item_id'] = $roleName;
        return $relationData;
    }

    /**
     * @Function 给角色删除用户
     * @Author Weihuaadmin@163.com
     */
    public static function delRoleByUserId($userIds,$role,$type = 1){
        $userIds = is_array($userIds) ? $userIds : explode(',',$userIds);
        $authManager = \Yii::$app->authManager;
        $relationModel = new AdminAuthRelation();
        foreach ($userIds as $k => $userId){
            if($type > 2){
                $_relationModel = clone $relationModel;
                $data = self::_relationData($role,$type,$userId);
                $key = ToolUtil::getSelectType(AdminAuthRelation::getKey(),$type);
                $_relationModel::deleteAll("type = :type AND adminid = :adminid AND {$key} = :key",[':type' => $type,':adminid'=>$userId, ':key' => $data[$key]]);
                $role = $data['auth_item_id'];
            }else{
                $role_id = User::findValueByWhere(['user_id' => $userId],'role_id',['user_id' => SORT_DESC]);
                $role_id = explode(',',$role_id);
                $k = array_search($role,$role_id);
                if($k !== false){
                    array_splice($role_id,$k,1);
                }
                User::updateAll(['role_id' => implode(',',$role_id)],'user_id = :user_id',[':user_id' => $userId]);
            }
            $authManager->revoke($authManager->getRole($role),$userId);
        }
        return true;
    }


    /**
     * @Function 创建菜单
     * @param $rouMap 菜单标识
     * @param $menuName 菜单名称
     * @Author Weihuaadmin@163.com
     */
    public static function createMenu($rouMap,$menuName){
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $exist = self::_verifyExist($rouMap);
            if(!empty($exist)){
                return ToolUtil::returnMsg(false,'菜单已存在');
            };
            $menuId = $rouMap.'_00';
            $menuData = [
                'name' => $menuId,
                'description' => $menuName,
                'type' => AuthPermission::TYPE_PERMISSION,
                'status' => 1, 'is_menu' => 1
            ];
            $authData = $menuData;
            $authData['name'] = (string)$rouMap;
            $authData['type'] = AuthPermission::TYPE_APP;
            $authData['is_menu'] = 0;
            $itemModel = new AuthItem();
            $_itemModel = clone $itemModel;
            $itemModel->setAttributes($menuData,false);
            $_itemModel->setAttributes($authData,false);
            if($itemModel->save() && $_itemModel->save()){
                $transaction->commit();
                //新加的菜单都需要给超级管理员添加
                $superRole = \Yii::$app->getAuthManager()->superRole;
                $authItemChildModel = new AuthItemChild();
                $authItemChildModel->addChild([
                    'parent' => $superRole,
                    'child' => $menuId
                ]);
                return ToolUtil::returnMsg(true);
            }
            $transaction->rollBack();
            $error = $itemModel->getModelError();
            $error .= $_itemModel->getModelError();
            return ToolUtil::returnMsg(false,$error);
        }catch (\Exception $e){
            LogUtil::setExceptionLog('异常记录',$e);
            return ToolUtil::returnMsg(false);
        }
        
    }
        /**
     * @Function 修改菜单标识
     * @param $rouMap 菜单标识
     * @param $menuName 菜单名称
     * @Author wuhaibo
     */
    public static function updateMenu($oldName,$newName){
        $isExist = self::findValueByWhere(['name' => $newName],["name"],["name"=>$newName]);
        if($isExist){
            return ToolUtil::returnAjaxMsg(false,'抱歉该标识已经存在！');
        }
        $menuInfo = self::findOne($oldName);
        if($menuInfo){
            $transaction = \Yii::$app->db->beginTransaction();
            try{
                $updateRes = self::updateAll(['updated_at' => time(), 'name' => $newName],"name =:name",[":name"=>$oldName]);
                self::updateAll(['updated_at' => time(),'parent_name' => $newName],"parent_name=:parent_name",[":parent_name"=>$oldName]);
                if($updateRes){
                    $transaction->commit();
                    return ToolUtil::returnAjaxMsg(true,'操作成功');
                }
                $transaction->rollBack();
                return ToolUtil::returnAjaxMsg(false,'操作失败');
            }catch (\Exception $e){
                $transaction->rollBack();
                LogUtil::setExceptionLog('update Menu',$e);
                return ToolUtil::returnAjaxMsg(false,'操作失败');
            }
        }
        return ToolUtil::returnAjaxMsg(false,'操作失败');
    }
}
