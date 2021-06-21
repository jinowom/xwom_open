<?php

namespace api\models;

use Yii;
use common\models\VerifyCodeHistory;
use common\utils\ToolUtil;

/**
 * This is the model class for table "xportal_member".
 *
 * @property int $member_id 会员id
 * @property string $member_user 会员名
 * @property string $member_pwd 密码
 * @property string $member_encrypt 加密因子
 * @property string $member_name 会员昵称
 * @property string $member_mobile 手机号
 * @property int $member_login_time 登录时间
 * @property int $member_quit_time 退出时间
 * @property string $member_register_ip 注册ip
 * @property string $member_last_ip 最后登录ip
 * @property int $member_login_num 用户登录次数
 * @property int $member_group_id 用户所在组
 * @property int $member_have_message 是否有推送消息
 * @property int $member_check_status 审核状态
 * @property string $token 帐号激活码
 * @property int $token_exptime 激活码有效期
 * @property string $head_portrait_small 会员小头像
 * @property string $head_portrait_big 会员大头像
 * @property int $siteid 站点id
 * @property int $created_at 注册时间
 * @property int $updated_at 修改时间
 * @property int $is_del 是否删除 0否 1是
 * @property string $login_ip 登录ip
 * @property int $login_time 最后登录时间
 * @property int $login_count 累计登录次数
 * @property int $error_count 登录错误次数
 * @property int $allow_login_time 允许登录时间
 */
class XportalMember extends \yii\db\ActiveRecord
{
    const MEMBER_CHECK_STATUS = 1;
    public $code;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'xportal_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_login_time', 'member_quit_time', 'member_login_num', 'member_group_id', 'member_have_message', 'member_check_status', 'token_exptime', 'siteid', 'created_at', 'updated_at', 'is_del','login_time','login_count','error_count','allow_login_time'], 'integer'],

            [['login_ip'], 'string'],

            [['member_user'], 'string', 'max' => 50],
            [['member_pwd'], 'string', 'max' => 255],
            [['member_encrypt'], 'string', 'max' => 6],
            [['member_name'], 'string', 'max' => 30],
            
            [['member_register_ip', 'member_last_ip'], 'string', 'max' => 15],
            [['token'], 'string', 'max' => 450],
            [['head_portrait_small', 'head_portrait_big'], 'string', 'max' => 500],

            //'code'
            // [['member_user', 'member_pwd', 'member_mobile'], 'required'],
            ['member_mobile','match','pattern'=>'/^1[0-9]{10}$/','message'=>'{attribute}必须为1开头的11位纯数字'],
            ['member_mobile', 'string', 'min'=>11,'max' => 11],
            ['member_mobile', 'unique', 'targetClass' => '\api\models\XportalMember', 'message' => '该手机号码已经被占用.'],
            ['member_user', 'filter', 'filter' => 'trim'],
            ['member_user', 'unique', 'targetClass' => '\api\models\XportalMember', 'message' => '该用户名已经被占用.'],
            // ['member_user', 'string', 'min' => 2, 'max' => 12],
            // ['member_user','match','pattern'=>'/^[a-zA-Z0-9_]+$/','message'=>'{attribute}只能由英文字母、数字、下划线组成'],
            ['code', 'integer'],
            ['code', 'string', 'min'=>4,'max' => 4],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'member_id' => 'Member ID',
            'member_user' => 'Member User',
            'member_pwd' => 'Member Pwd',
            'member_encrypt' => 'Member Encrypt',
            'member_name' => 'Member Name',
            'member_mobile' => 'Member Mobile',
            'member_login_time' => 'Member Login Time',
            'member_quit_time' => 'Member Quit Time',
            'member_register_ip' => 'Member Register Ip',
            'member_last_ip' => 'Member Last Ip',
            'member_login_num' => 'Member Login Num',
            'member_group_id' => 'Member Group ID',
            'member_have_message' => 'Member Have Message',
            'member_check_status' => 'Member Check Status',
            'token' => 'Token',
            'token_exptime' => 'Token Exptime',
            'head_portrait_small' => 'Head Portrait Small',
            'head_portrait_big' => 'Head Portrait Big',
            'siteid' => 'Siteid',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_del' => 'Is Del',
            'login_ip' => 'Login Ip',
            'login_time' => 'Login Time',
            'login_count' => 'Login Count',
            'error_count' => 'Error Count',
            'allow_login_time' => 'Allow Login Time',
        ];
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->member_pwd = Yii::$app->security->generatePasswordHash($password);
        return $this->member_pwd;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAccessToken()
    {
        $this->token = Yii::$app->security->generateRandomString();
        return $this->token;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['member_user' => $username, 'member_check_status' => self::MEMBER_CHECK_STATUS]);
    }

    /**
     * Finds user by mobile
     *
     * @param int $mobile
     * @return static|null
     */
    public static function findByMobile($mobile)
    {
        return static::findOne(['member_mobile' => $mobile, 'member_check_status' => self::MEMBER_CHECK_STATUS]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->member_pwd);
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface|null the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token, 'member_check_status' => self::MEMBER_CHECK_STATUS]);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        // TODO: Implement getId() method.
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled. The returned key will be stored on the
     * client side as a cookie and will be used to authenticate user even if PHP session has been expired.
     *
     * Make sure to invalidate earlier issued authKeys when you implement force user logout, password change and
     * other scenarios, that require forceful access revocation for old sessions.
     *
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    /**
     *用户列表
     */
    public static function getMemberList($parames=""){

        return self::find()->andWhere(['is_del' =>0])
                           ->andFilterWhere(['or',
                                            ['=','member_id',$parames],
                                            ['=','member_mobile',$parames],
                                            ['like','member_user',$parames],
                                            ['like','member_name',$parames]
                                        ]);
    }

    //提交修改表单
    public static function updateDo($request){
        // 修改信息入库
        $model = self::findOne($request['member_id']);
        $model->attributes = $request;
        if ($model->save()) {
            return true;
        } else {
            return json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 添加用户
     */
    public static function createDo($request){
        // 添加信息入库
        $trans = \Yii::$app->db->beginTransaction();
        $model = new XportalMember();
        $model->attributes = $request;
        if ($model->save()) {
            $trans->commit();
            return true;
        } else {
            $trans->rollBack();
            return $model->getErrors();
        }
    }

}
