<?php
/**
 * Created by PhpStorm.
 * User: WANGWEIHUA
 * Date: 2019/4/21
 * Time: 0:02
 */

namespace common\models;
use common\models\auth\AuthItem;
use common\models\auth\AuthPermission;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\rbac\Item;

class AdminManager extends DbManager
{
    public $superRole = 'admin'; //超级角色

    /**
     * @Function 通过用户标识获取所有权限
     * @Author Weihuaadmin@163.com
     * @param int|string $user_id
     * @return array
     */
    public function getPermissionsByUser($user_id){
        if(empty($user_id)){
            return [];
        }
        $roles = array_keys($this->getRolesByUser($user_id));
        if(ArrayHelper::isIn($this->superRole,$roles)){
            $permissions = $this->getPermissionsByName($this->superRole);
        }else{
            $permissions = $this->getInheritedPermissionsByUser($user_id);
        }
        return $permissions;
    }

    /**
     * @Function 通过角色标识获取所有权限
     * @Author Weihuaadmin@163.com
     * @param $name  auth_item的唯一标示
     * @return array
     */
    public function getPermissionsByName($name){
        if(empty($name)){
            return [];
        }
        $childrenList = $this->getChildrenList();
        $result = [];
        $this->getChildrenRecursive($name, $childrenList, $result);
        if (empty($result)) {
            return [];
        }
        $permissions = [];
        $authItem = AuthItem::findAllByWhere(['type' => Item::TYPE_PERMISSION,'name' => array_keys($result)],null,['order_sort'=> SORT_DESC,'created_at' => SORT_DESC]);
        foreach ($authItem as $row){
            $permissions[$row['name']] = $this->populateItem($row);
        }
        return $permissions;
    }

    /**
     * @Function 通过用户标识获取所有角色
     * @Author Weihuaadmin@163.com
     * @param int|string $userId
     * @return array|\yii\rbac\Role[]
     */
    public function getRolesByUser($userId)
    {
        return parent::getRolesByUser($userId);
    }

    /**
     * @Function 重写权限返回值
     * @Author Weihuaadmin@163.com
     * @param array $row
     * @return mixed
     */
   protected function populateItem($row)
   {
       $class = AuthPermission::className();
       if (!isset($row['data']) || ($data = @unserialize(is_resource($row['data']) ? stream_get_contents($row['data']) : $row['data'])) === false) {
           $data = null;
       }
       return new $class([
           'name' => $row['name'],
           'type' => $row['type'],
           'description' => $row['description'],
           'ruleName' => $row['rule_name'] ?: null,
           'data' => $data,
           'createdAt' => $row['created_at'],
           'updatedAt' => $row['updated_at'],
           'parentName' => $row['parent_name'],
           'status' => $row['status'],
           'orderSort' => $row['order_sort'],
           'icon' => $row['icon'],
           'isMenu' => $row['is_menu'],
       ]);
   }

    /**
     * @Function 重写创建权限模型
     * @Author Weihuaadmin@163.com
     * @param string $name
     * @return AuthPermission
     */
    public function createPermission($name){
        $permission = new AuthPermission();
        $permission->name = $name;
        return $permission;
    }

    /**
     * @Function 重写添加菜单
     * @Author Weihuaadmin@163.com
     * @param Item $item
     * @return bool
     * @throws \yii\db\Exception
     */
    protected function addItem($item)
    {
        $time = time();
        if ($item->createdAt === null) {
            $item->createdAt = $time;
        }
        if ($item->updatedAt === null) {
            $item->updatedAt = $time;
        }
        $this->db->createCommand()
            ->insert($this->itemTable, [
                'name' => $item->name,
                'type' => $item->type,
                'description' => $item->description,
                'rule_name' => $item->ruleName,
                'data' => $item->data === null ? null : serialize($item->data),
                'created_at' => $item->createdAt,
                'updated_at' => $item->updatedAt,
                'parent_name' => $item->parentName,
                'status' => $item->status,
                'order_sort' => $item->orderSort,
                'icon' => $item->icon,
                'is_menu' => $item->isMenu,
            ])->execute();

        $this->invalidateCache();

        return true;
    }

    /**
     * @Function 重写更新菜单
     * @Author Weihuaadmin@163.com
     * @param string $name
     * @param Item $item
     * @return bool
     * @throws \yii\db\Exception
     */
    public function updateItem($name, $item)
    {
        if ($item->name !== $name && !$this->supportsCascadeUpdate()) {
            $this->db->createCommand()
                ->update($this->itemChildTable, ['parent' => $item->name], ['parent' => $name])
                ->execute();
            $this->db->createCommand()
                ->update($this->itemChildTable, ['child' => $item->name], ['child' => $name])
                ->execute();
            $this->db->createCommand()
                ->update($this->assignmentTable, ['item_name' => $item->name], ['item_name' => $name])
                ->execute();
        }

        $item->updatedAt = time();

        $this->db->createCommand()
            ->update($this->itemTable, [
                'name' => $item->name,
                'description' => $item->description,
                'rule_name' => $item->ruleName,
                'data' => $item->data === null ? null : serialize($item->data),
                'updated_at' => $item->updatedAt,
                'status' => $item->status,
                'order_sort' => $item->orderSort,
                'icon' => $item->icon,
                'is_menu' => $item->isMenu,
            ], [
                'name' => $name,
            ])->execute();

        $this->invalidateCache();

        return true;
    }

    /**
     * @Function 实例化
     * @Author Weihuaadmin@163.com
     * @param string $name
     * @return AuthPermission
     */
    public function createRole($name, $type = AuthPermission::TYPE_ROLE)
    {
        $permission = new AuthPermission();
        $permission-> type = $type;
        return $permission;
    }

    public function getRole($name)
    {
        $item = $this->getItem($name);
        return $item instanceof Item && $item->type == Item::TYPE_PERMISSION ? null: $item;
    }

    public function getItem($name)
    {
        return parent::getItem($name); // TODO: Change the autogenerated stub
    }


    /**
     * @Function 删除权限
     * @Author Weihuaadmin@163.com
     * @param $authName
     */
    public function delAuth($authName){
        $authInfo = AuthItem::findValueByWhere(['name' => $authName],null,['order_sort' => SORT_DESC]);
        $auths = $this->populateItem($authInfo);
        return $this->removeItem($auths);
    }
    

}