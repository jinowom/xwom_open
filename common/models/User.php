<?php
namespace common\models;

use common\models\auth\AdminAuthRelation;
use common\utils\ToolUtil;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use api\models\XportalMember;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends BaseModel implements IdentityInterface
{
    use \common\traits\MapTrait;
    
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 11;
    const STATUS_ACTIVE = 10;
    const SCENARIO_ADD = 'add';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_UPDATE_USER = 'updateUser';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin}}';
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
            [['real_name', 'password_hash', 'role_id', 'created_at', 'updated_at'], 'required','message' => '{attribute}不能为空'],
            [['role', 'status', 'created_at', 'updated_at', 'dep_isleader', 'team_leader'], 'integer','message' => '{attribute}必须是数字'],
            [['username', 'password_hash', 'password_reset_hash', 'email', 'email_verify_token', 'access_token', 'role_id'], 'string', 'max' => 255, 'message' => '{attribute}不能大于255个字符'],
            [['real_name'], 'string', 'max' => 60, 'message' => '{attribute}不能大于255个字符'],
            [['phone'], 'string', 'max' => 11,'message' => '{attribute}不能大于11个数字'],
            [['auth_key'], 'string', 'max' => 32, 'message' => '{attribute}不能大于32个字符'],
            [['phone','email'], 'unique', 'message' => '{attribute}已占用' ,'on' => [self::SCENARIO_ADD]],
            [['phone','email','username'], 'chkUnique','on' => [self::SCENARIO_UPDATE,self::SCENARIO_UPDATE_USER]],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD => ['real_name','username','password_hash','role_id','created_at','updated_at',
                'status','dep_isleader','team_leader','email','phone'],
            self::SCENARIO_UPDATE => ['real_name','username','password_hash','role_id','created_at','updated_at',
                'status','dep_isleader','team_leader','email','phone'],
            self::SCENARIO_UPDATE_USER => ['user_id','email','phone']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '自增主键',
            'username' => '用户名',
            'real_name' => '真实姓名',
            'password_hash' => '密码hash',
            'password_reset_hash' => '重置密码token',
            'email' => '邮件',
            'email_verify_token' => '邮箱验证token',
            'phone' => '手机号',
            'auth_key' => '用于自动登录',
            'access_token' => 'Api访问token',
            'role' => '角色，预留字段 暂没有使用',
            'role_id' => '角色ID',
            'status' => '是否启用 0 删除 9 禁用 10 活跃用户',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'dep_isleader' => '0-代表普通人员，1-代表部门管理者',
            'team_leader' => '0-代表普通人员，1-代表Team管理者',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDep()
    {
        return $this->hasOne(AdminDep::className(), ['depid' => 'dep_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(AdminTeam::className(), ['teamid' => 'team_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(AdminUnit::className(), ['unitid' => 'unit_id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return XportalMember::find()
            ->where(['token' => $token, 'member_check_status' => XportalMember::MEMBER_CHECK_STATUS])
            ->andWhere(['>', 'token_exptime', time()])->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * 生成access_token
     * @return mixed|string
     */
    public static function generateAccessToken()
    {
        $access_token = Yii::$app->security->generateRandomString();

        return $access_token;
    }


    /**
     * 根据用户ID获取单位、团队、部门
     * @Author: Weihuaadmin@163.com
     * @param $userId
     */
    public static function getdataByUser($userId,$type = AdminAuthRelation::TYPE_UNIT){
        $key = ToolUtil::getSelectType(AdminAuthRelation::getKey(),$type,'*');
        if(!empty(\Yii::$app->session->get($key))){
            $return = \Yii::$app->session->get($key);
        }else{
            $tableName = AdminAuthRelation::tableName();
            switch ($type){
                case AdminAuthRelation::TYPE_UNIT:
                    $relations = AdminAuthRelation::find()->select(['u.'.$key])->leftJoin(AdminUnit::tableName(). ' u',"u.unitid = {$tableName}.unitid")->where(['type' => $type,'adminid' => $userId])->asArray()->all();
                    break;
                case AdminAuthRelation::TYPE_DEP:
                    $relations = AdminAuthRelation::find()->select(['d.'.$key])->leftJoin(AdminDep::tableName(). ' d',"d.depid = {$tableName}.depid")->where(['type' => $type,'adminid' => $userId])->asArray()->all();
                    break;
                case AdminAuthRelation::TYPE_TEAM:
                    $relations = AdminAuthRelation::find()->select(['t.'.$key])->leftJoin(AdminTeam::tableName(). ' t',"t.teamid = {$tableName}.teamid")->where(['type' => $type,'adminid' => $userId])->asArray()->all();
                    break;
                default:
                    $relations = AdminAuthRelation::find()->select([$key])->where(['type' => $type,'adminid' => $userId])->asArray()->all();
            }
            $return = array_unique(ArrayHelper::getColumn($relations,$key));
            \Yii::$app->session->set($key,$return);
        }
        return $return;

    }

    /**
     * @Function 删除管理员
     * @Author Weihuaadmin@163.com
     * @param $userIds
     * @return array
     */
    public static function delAdmin($userIds){
        $return = ToolUtil::returnAjaxMsg(false,'操作失败');
        $trans = \Yii::$app->db->beginTransaction();
        $auth = \Yii::$app->getAuthManager();
        try {
            $adminInfo = self::findValueByWhere(['user_id' => $userIds],['user_id','username'],'user_id DESC');
            if(empty($adminInfo)){
                throw new \Exception("用户不存在!");
            }
            if($adminInfo['username'] == 'admin'){
                throw new \Exception("超级管理员不能删除!");
            };
            if (!self::updateAll(['status' => self::STATUS_DELETED],'user_id = :user_id', [":user_id" => $userIds])) {
                throw new \Exception("用户删除失败!");
            }
            $auth ->revokeAll($userIds);
            $trans->commit();
            $return = ToolUtil::returnAjaxMsg(true,'操作成功');
        } catch (\Exception $e) {
            $return['msg'] = $e->getMessage();
            $trans->rollback();
        }
        return $return;
    }

    /**
     * @Function 添加管理员
     * @Author Weihuaadmin@163.com
     * @param $data 数据
     * @return bool
     * @throws \yii\base\Exception
     */
    public function adminAdd($data,$userId = null)
    {
        $tran = \Yii::$app->db->beginTransaction();
        if(!empty($userId)){
            $this->user_id = $userId;
            $this->scenario = 'update';
            $password_hash = self::findValueByWhere(['user_id'=>$userId],'password_hash',['user_id' => SORT_DESC]);
        }else{
            $this->scenario = 'add';
            $password_hash = $data['user']['pass'];
        }
        $unit = $data['user']['unit'];
        $roles = $data['user']['role_id'];
        $userData = [
            'password_hash' => $password_hash,
            'created_at' => time(),
            'updated_at' => time(),
            'role_id' => implode(',',$roles),
            'status' => self::STATUS_ACTIVE
        ];
        $data = ArrayHelper::merge($data['user'],$userData);
        if ($this->load($data,'') && $this->validate()) {
            if(!empty($userId)){
                $this->password_hash = $this->password_hash;
                $this->setOldAttribute('user_id',$userId);
            }else{
                $this->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($this->password_hash);
            }
            if ($this->save()) {
                $authManager = \Yii::$app->authManager;
                if(!empty($userId)){
                    $authManager->revokeAll($userId);
                }else{
                    $userId = $this->getPrimaryKey();
                }
                foreach ($roles as $role){
                    $authManager->assign($authManager->getRole($role), $userId);
                }

                //添加单位
                $relationModel = new AdminAuthRelation();
                $relaRes = $relationModel->createRow([
                    'adminid' => $userId,
                    'unitid' => $unit,
                    'siteid' => 1,
                    'type' => AdminAuthRelation::TYPE_UNIT,
                ]);
                if(!empty($relaRes['status'])){
                    $tran->commit();
                    return true;
                }else{
                    $tran->rollBack();
                    return false;
                }
            }
            $tran->rollBack();
            return false;
        }
        $tran->rollBack();
        return false;
    }

    /**
     * @Function 检测属性是否已经存在 【更新操作使用该验证】
     * @Author Weihuaadmin@163.com
     */
    public function chkUnique($attribute, $params)
    {
        $originalUserInfo = self::find()->select($attribute)->where(['user_id'=>$this->user_id])->asArray()->one();
        if($originalUserInfo[$attribute] != $this->$attribute){
            if(self::find()->where([$attribute => $this->$attribute])->asArray()->one()){
                $this->addError($attribute,ToolUtil::getSelectType($this->attributeLabels(),$attribute).'已占用');
            };
        }
    }

    /**
     * @Function 修改信息
     * @param $postData 修改数据
     * @param $userId 修改用户ID
     * @Author Weihuaadmin@163.com
     */
    public function updateData($postData,$userId){
        $this->scenario = self::SCENARIO_UPDATE_USER;
        $postData['user']['user_id'] =  $userId;
        $postData['user']['phone'] =  !empty($postData['user']['phone']) ? $postData['user']['phone'] : $postData['phone'];
        $this->setOldAttribute('user_id',$userId);
        $code = $postData['user']['code'];
        if($code){
            $verifyRes = VerifyCodeHistory::verifySms($postData['user']['phone'],$code,\Yii::$app->params['SMS']['MODIFY_CODE']);
            if(empty($verifyRes['status'])){
                return ToolUtil::returnAjaxMsg(false,$verifyRes['msg']);
            }
        }
        $this->load($postData,'user');
        if($this->validate()){
            if($this->save()){
                return ToolUtil::returnAjaxMsg(true,'操作成功');
            }
            return ToolUtil::returnAjaxMsg(true,'操作失败');
        }else{
            return ToolUtil::returnAjaxMsg(false,$this->getModelError());
        }
    }


    /**
     * 根据用户id，获取用户详情
     * @param $userId int 用户id
     * @return mixed
     * @author rjl
     */
    public function getAdminInfo($userId)
    {
        $adminInfo = self::findValueByWhere(['user_id' => $userId],[],'user_id DESC');

        return $adminInfo;
    }
}
