<?php
/**
 * 重写权限模型
 * Created by PhpStorm.
 * User: WANGWEIHUA
 * Date: 2019/10/19
 * Time: 0:34
 */

namespace common\models\auth;

use yii\rbac\Permission;

class AuthPermission extends Permission
{
    //类型 1表示角色 2表示权限 3 单位 4 部门 5 团队 6 子系统 7 子站点
    const TYPE_ROLE = 1;
    const TYPE_PERMISSION = 2;
    const TYPE_UNIT = 3;    //
    const TYPE_DEP = 4;
    const TYPE_TEAM = 5;
    const TYPE_APP = 6;
    const TYPE_SITE = 7;

    const STATUS = 1; //开启
    const STATUS_DISABLE  = 0; //禁用

    //父节点
    public $parentName;

    // 状态  0禁用 1启用
    public $status;

    //序号 默认100
    public $orderSort;

    //图标
    public $icon;

    //是否显示菜单
    public $isMenu;
}