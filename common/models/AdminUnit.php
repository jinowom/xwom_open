<?php

namespace common\models;

use common\utils\ToolUtil;
use Yii;

/**
 * This is the model class for table "{{%admin_unit}}".
 *
 * @property int $unitid 单位ID
 * @property string $name 单位名称
 * @property string $description 单位描述
 * @property int $u_status 状态
 * @property int $siteid 站点ID
 * @property int $sort_id 排序ID
 * @property string $app_id 子系统Id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $is_del 是否删除 0否 1是
 * @property string $auth_item_id 关联auth_item的name字段
 *
 * @property Admin[] $admins
 * @property AdminUnitStatus $uStatus
 */
class AdminUnit extends \common\models\BaseModel
{
    const STATUS_DELETED = 1;
    const STATUS_NO_DELETED = 0;
    const STATUS_INACTIVE = 11;
    const STATUS_ACTIVE = 10;
    const SCENARIO_ADD = 'add';
    const SCENARIO_UPDATE = 'update';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_unit}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'siteid', 'sort_id'], 'required'],
            [['u_status', 'siteid', 'sort_id', 'app_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['description'], 'string', 'max' => 255],
            [['auth_item_id'], 'string', 'max' => 50],
            [['u_status'], 'exist', 'skipOnError' => true, 'targetClass' => AdminUnitStatus::className(), 'targetAttribute' => ['u_status' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'unitid' => '单位ID',
            'name' => '单位名称',
            'description' => '单位描述',
            'u_status' => '状态',
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
        return $this->hasMany(Admin::className(), ['unit_id' => 'unitid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUStatus()
    {
        return $this->hasOne(AdminUnitStatus::className(), ['id' => 'u_status']);
    }
    
    /**
     * @删除单位部门操作
     */
    public  static function delUnit($uIds){
        $return = ToolUtil::returnAjaxMsg(false,'操作失败');
        $trans = \Yii::$app->db->beginTransaction();
        $auth = \Yii::$app->getAuthManager();
        try {
            $unitInfo = self::findValueByWhere(['unitid' => $uIds],['unitid','name'],'unitid DESC');
            if(empty($unitInfo)){
                throw new \Exception("单位不存在!");
            }
            if (!self::updateAll(['is_del' => self::STATUS_DELETED],'unitid = :unitid', [":unitid" => $uIds])) {
                throw new \Exception("单位删除失败!");
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
