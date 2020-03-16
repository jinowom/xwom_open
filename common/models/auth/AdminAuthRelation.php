<?php

namespace common\models\auth;

use common\models\User;
use common\utils\ToolUtil;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%admin_auth_relation}}".
 *
 * @property int $id 主键
 * @property string $unitid 单位Id
 * @property string $depid 部门ID
 * @property string $teamid 团队ID
 * @property string $appid 子系统ID
 * @property string $siteid 子站点Id
 * @property string $adminid Admin Id
 * @property int $status 状态 10 可用 11 不可用
 * @property int $type 类型 0 单位 1 部门 2 团队 3 子系统 4 子站点
 * @property string $inputtime 创建时间
 * @property string $updatetime 更新时间
 */
class AdminAuthRelation extends \common\models\BaseModel
{
    const TYPE_UNIT = '0';
    const TYPE_DEP = '1';
    const TYPE_TEAM = '2';
    const TYPE_APP = '3';
    const TYPE_SITE = '4';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_auth_relation}}';
    }

    /**
     * 获取key
     * @Author: Weihuaadmin@163.com
     * @return array
     */
    public static function getKey(){
        return [
          self::TYPE_APP => 'appid',
          self::TYPE_UNIT => 'unitid',
          self::TYPE_DEP => 'depid',
          self::TYPE_TEAM => 'teamid',
          self::TYPE_SITE => 'siteid',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    # 创建之前
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    # 修改之前
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                #设置默认值
                'value' => time()
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unitid', 'depid', 'teamid', 'appid', 'siteid', 'adminid', 'type', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'unitid' => '单位Id',
            'depid' => '部门ID',
            'teamid' => '团队ID',
            'appid' => '子系统ID',
            'siteid' => '子站点Id',
            'adminid' => 'Admin Id',
            'type' => '类型 0 单位 1 部门 2 团队 3 子系统 4 子站点',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 通过团队ID获取users信息
     * @Author: Weihuaadmin@163.com
     */
    public static function getUsersByTeam($teamId){
            return self::find()->select(['*'])->leftJoin(User::tableName(),"adminid = user_id")
                ->where(['teamid' => $teamId, 'type' => self::TYPE_TEAM])
                ->asArray()->all();
    }
    /**
     * 通过部门ID获取users信息
     */
    public static function getUsersByDep($depId){
            return self::find()->select(['*'])->leftJoin(User::tableName(), "adminid = user_id")
                ->where(['depid' => $depId, 'type' => self::TYPE_DEP])
                ->asArray()->all();

    }

    /**
     * @Function：添加员工单位关系表
     * @Author: Weihuaadmin@163.com
     */
    public function createRow($data){
        if($this->load($data,'') && $this->validate()){
            if($this->save()){
                return ToolUtil::returnMsg(true);
            }else{
                return ToolUtil::returnMsg(false,'添加失败');
            }
        }else{
            return ToolUtil::returnMsg(false,$this->getModelError());
        }
    }
}
