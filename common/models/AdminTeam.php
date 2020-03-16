<?php

namespace common\models;

use common\utils\ToolUtil;
use Yii;

/**
 * This is the model class for table "{{%admin_team}}".
 *
 * @property int $teamid 团队ID
 * @property string $name 团队名称
 * @property string $description 团队描述
 * @property int $father_id 父级ID
 * @property int $t_status 状态
 * @property int $unit_id 单位ID
 * @property int $sort_id 排序ID
 * @property int $is_del 是否删除 0否 1是
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property string $auth_item_id 关联auth_item的name字段
 *
 * @property Admin[] $admins
 * @property AdminTeamStatus $tStatus
 */
class AdminTeam extends \common\models\BaseModel
{
    const STATUS_DELETED = 1;
    const STATUS_INACTIVE = 11;
    const STATUS_ACTIVE = 10;
    const IS_LEADER =1;
    const IS_NOLEADER = 0;
    const SCENARIO_ADD = 'add';
    const SCENARIO_UPDATE = 'update';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_team}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'unit_id'], 'required'],
            [['father_id', 't_status', 'unit_id', 'sort_id', 'is_del', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['description'], 'string', 'max' => 255],
            [['auth_item_id'], 'string', 'max' => 50],
            [['t_status'], 'exist', 'skipOnError' => true, 'targetClass' => AdminTeamStatus::className(), 'targetAttribute' => ['t_status' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'teamid' => '团队ID',
            'name' => '团队名称',
            'description' => '团队描述',
            'father_id' => '父级ID',
            't_status' => '状态',
            'unit_id' => '单位ID',
            'sort_id' => '排序ID',
            'is_del' => '是否删除 0否 1是',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'auth_item_id' => '关联auth_item的name字段',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmins()
    {
        return $this->hasMany(Admin::className(), ['team_id' => 'teamid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTStatus()
    {
        return $this->hasOne(AdminTeamStatus::className(), ['id' => 't_status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit(){
        return $this->hasOne(AdminUnit::className(), ['unitid' => 'unit_id'])->asArray()->one();
    }

    /**
     * @删除单位部门操作
     */
    public  static function delTeam($uIds){
        $return = ToolUtil::returnAjaxMsg(false,'操作失败');
        $trans = \Yii::$app->db->beginTransaction();
        $isExitTeam = [];
        //$auth = \Yii::$app->getAuthManager();
        try {
            $unitInfo = self::findValueByWhere(['teamid' => $uIds,'is_del'=>0],['teamid','name'],'teamid DESC');
            if(empty($unitInfo)){
                throw new \Exception("团队不存在!");
            }
            $isExitTeam  = self::findValueByWhere(['father_id' => $uIds,'is_del'=>0],['teamid','name'],'teamid DESC');
            if(!empty($isExitTeam)){
                throw new \Exception("请先移除子团队!");
            }
            if (!self::updateAll(['is_del' => self::STATUS_DELETED],'teamid = :teamid', [":teamid" => $uIds])) {
                throw new \Exception("团队删除失败!");
            }
            /* //@todo 多维度删除
             if(!$auth ->revokeAll($uIds)){
                 throw new \Exception("移除权限!");
             };*/
            $trans->commit();
            $return = ToolUtil::returnAjaxMsg(true,'操作成功');
        } catch (\Exception $e) {
            $return['msg'] = $e->getMessage();
            $trans->rollback();
        }
        return $return;
    }
}
