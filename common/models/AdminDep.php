<?php

namespace common\models;

use common\utils\ToolUtil;
use Yii;

/**
 * This is the model class for table "{{%admin_dep}}".
 *
 * @property int $depid 部门ID
 * @property string $name 部门名称
 * @property string $description 部门描述
 * @property int $d_status 状态
 * @property int $father_id 父级ID
 * @property int $unit_id 单位ID
 * @property int $siteid 站点ID
 * @property int $sort_id 排序ID
 * @property string $app_id 子系统Id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $is_del 是否删除 0否 1是
 * @property string $auth_item_id 关联auth_item的name字段
 *
 * @property Admin[] $admins
 * @property AdminDepStatus $dStatus
 */
class AdminDep extends \common\models\BaseModel
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
        return '{{%admin_dep}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'unit_id', 'siteid'], 'required'],
            [['d_status', 'father_id', 'unit_id', 'siteid', 'sort_id', 'app_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['description'], 'string', 'max' => 255],
            [['auth_item_id'], 'string', 'max' => 50],
            [['d_status'], 'exist', 'skipOnError' => true, 'targetClass' => AdminDepStatus::className(), 'targetAttribute' => ['d_status' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'depid' => '部门ID',
            'name' => '部门名称',
            'description' => '部门描述',
            'd_status' => '状态',
            'father_id' => '父级ID',
            'unit_id' => '单位ID',
            'siteid' => '站点ID',
            'sort_id' => '排序ID',
            'app_id' => '子系统Id',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'is_del' => '是否删除 0否 1是',
            'auth_item_id' => '关联auth_item的name字段',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmins()
    {
        return $this->hasMany(Admin::className(), ['dep_id' => 'depid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDStatus()
    {
        return $this->hasOne(AdminDepStatus::className(), ['id' => 'd_status']);
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
    public  static function delDep($dIds){
        $isExitDep =[];
        $return = ToolUtil::returnAjaxMsg(false,'操作失败');
        $trans = \Yii::$app->db->beginTransaction();
        //$auth = \Yii::$app->getAuthManager();
        try {
            $unitInfo = self::findValueByWhere(['depid' => $dIds,'is_del'=>0],['depid','name'],'depid DESC');
            if(empty($unitInfo)){
                throw new \Exception("部门不存在!");
            }
            $isExitDep  = self::findValueByWhere(['father_id' => $dIds,'is_del'=>0],['depid','name'],'depid DESC');
            if(!empty($isExitDep)){
                throw new \Exception("请先移除子部门!");
            }
            if (!self::updateAll(['is_del' => self::STATUS_DELETED],'depid = :depid', [":depid" => $dIds])) {
                throw new \Exception("部门删除失败!");
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
