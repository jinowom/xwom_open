<?php

namespace backend\modules\xpaper\models;

use Yii;

/**
 * This is the model class for table "xp_member_group".
 *
 * @property int $group_id 用户组id
 * @property string $group_name 组名称
 * @property string $group_description 描述
 * @property int $group_order 排序
 * @property int $group_disabled 是否禁用
 * @property string $member_power
 *
 * @property XpMember[] $xpMembers
 */
class XpMemberGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'xp_member_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_name', 'group_description', 'group_order'], 'required'],
            [['group_order', 'group_disabled'], 'integer'],
            [['group_name'], 'string', 'max' => 30],
            [['group_description'], 'string', 'max' => 500],
            [['member_power'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'group_id' => Yii::t('app', '用户组id'),
            'group_name' => Yii::t('app', '组名称'),
            'group_description' => Yii::t('app', '描述'),
            'group_order' => Yii::t('app', '排序'),
            'group_disabled' => Yii::t('app', '是否禁用'),
            'member_power' => Yii::t('app', 'Member Power'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXpMembers()
    {
        return $this->hasMany(XpMember::className(), ['groupid' => 'group_id']);
    }

    /**
     * {@inheritdoc}
     * @return XpMemberGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XpMemberGroupQuery(get_called_class());
    }
}
