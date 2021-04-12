<?php
/**
 * This is the model class for table "Admin";
 * @package common\models;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2021-01-12 10:27 */
namespace common\models;

use Yii;

/**
 * This is the model class for table "admin".
 *
 * @property int $user_id 自增主键
 * @property string $username 用户名
 * @property string $real_name 真实姓名
 * @property string $password_hash 密码hash
 * @property string $password_reset_hash 重置密码token
 * @property string $email 邮件
 * @property string $email_verify_token 邮箱验证token
 * @property string $phone 手机号
 * @property string $auth_key 用于自动登录
 * @property string $access_token Api访问token
 * @property int $role 角色，预留字段 暂没有使用
 * @property string $role_id 角色ID
 * @property string $login_ip 登录ip
 * @property string $login_time 最后登录时间
 * @property int $login_count 累计登录次数
 * @property int $error_count 登录错误次数
 * @property string $allow_login_time 允许登录时间
 * @property int $status 是否启用 0 删除  10 激活  11 禁用
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $dep_isleader 0-代表普通人员，1-代表部门管理者
 * @property int $team_leader 0-代表普通人员，1-代表Team管理者
 */
class Admin extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['real_name', 'password_hash', 'role_id', 'created_at', 'updated_at'], 'required'],
            [['role', 'login_count', 'error_count', 'status', 'created_at', 'updated_at', 'dep_isleader', 'team_leader', 'login_time', 'allow_login_time'], 'integer'],
            [['username', 'password_hash', 'password_reset_hash', 'email', 'email_verify_token', 'access_token', 'role_id'], 'string', 'max' => 255],
            [['real_name', 'login_ip'], 'string', 'max' => 60],
            [['phone'], 'string', 'max' => 11],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'username' => 'Username',
            'real_name' => 'Real Name',
            'password_hash' => 'Password Hash',
            'password_reset_hash' => 'Password Reset Hash',
            'email' => 'Email',
            'email_verify_token' => 'Email Verify Token',
            'phone' => 'Phone',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'role' => 'Role',
            'role_id' => 'Role ID',
            'login_ip' => 'Login Ip',
            'login_time' => 'Login Time',
            'login_count' => 'Login Count',
            'error_count' => 'Error Count',
            'allow_login_time' => 'Allow Login Time',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'dep_isleader' => 'Dep Isleader',
            'team_leader' => 'Team Leader',
        ];
    }
}