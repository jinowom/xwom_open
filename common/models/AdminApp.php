<?php

namespace common\models;

use common\models\auth\AuthItem;
use common\models\auth\AuthPermission;
use common\utils\ToolUtil;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%admin_app}}".
 *
 * @property int $appid 应用ID
 * @property string $name 应用名称
 * @property string $description 应用描述
 * @property int $t_status 状态
 * @property int $sort_id 排序ID
 * @property int $is_del 是否删除 0否 1是
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property string $auth_item_id 关联auth_item的name字段
 *
 * @property AdminAppStatus $tStatus
 */
class AdminApp extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_app}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',// 自己根据数据库字段修改
                'updatedAtAttribute' => 'updated_at', // 自己根据数据库字段修改, // 自己根据数据库字段修改
//                'value'   => new Expression('NOW()'),
                'value'   => function(){return time();},
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['t_status', 'sort_id', 'is_del', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['name'], 'authItemIdRules'],
            [['description'], 'string', 'max' => 255],
            [['auth_item_id'], 'string', 'max' => 50],
            [['t_status'], 'exist', 'skipOnError' => true, 'targetClass' => AdminAppStatus::className(), 'targetAttribute' => ['t_status' => 'id']],
        ];
    }

    /**
     * @Function 权限标识规则
     * @Author Weihuaadmin@163.com
     * @param $attribute
     * @param $param
     */
    public function authItemIdRules($attribute,$param){
        $info = self::findOne($this->getOldAttribute('appid'));
        if(!empty($info) && empty($info['auth_item_id'])){
            $auth_item_id = 'App_'.$info['appid'];
            self::updateAll(['auth_item_id' => $auth_item_id],"appid =:appid",[':appid'=>$info['appid']]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'appid' => '应用ID',
            'name' => '应用名称',
            'description' => '应用描述',
            't_status' => '状态',
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
    public function getTStatus()
    {
        return $this->hasOne(AdminAppStatus::className(), ['id' => 't_status']);
    }

    public function addOrUpdate($data){
        if(isset($data['appid']) && !empty($data['appid'])){
            $this->setOldAttribute('appid',$data['appid']);
        }
        if($this->load($data,'app') && $this->validate()){
            $res = $this->save();
            if($res){
                $auth_item_id = self::findValueByWhere(['appid' => $this->getOldAttribute('appid')],['auth_item_id'],[]);
                AuthItem::createAuthItem([
                    'name' => $auth_item_id,
                    'description' => $this->name
                ],AuthPermission::TYPE_APP);

                return AuthItem::addChild($auth_item_id,$data['roles']);
            }
            return ToolUtil::returnMsg(false);
        }
        return ToolUtil::returnMsg(false,$this->getModelError());
    }
}
