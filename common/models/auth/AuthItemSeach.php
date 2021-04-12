<?php

namespace common\models\auth;

use Yii;

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
class AuthItemSeach extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'channel_id', 'catid', 'created_at', 'updated_at', 'status', 'order_sort', 'is_menu'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'parent_name', 'rule_name'], 'string', 'max' => 64],
            [['icon'], 'string', 'max' => 100],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'parent_name' => 'Parent Name',
            'description' => 'Description',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'channel_id' => 'Channel ID',
            'catid' => 'Catid',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'order_sort' => 'Order Sort',
            'icon' => 'Icon',
            'is_menu' => 'Is Menu',
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
}
