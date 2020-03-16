<?php

namespace backend\modules\xpaper\models;

use Yii;

/**
 * This is the model class for table "xp_member".
 *
 * @property int $id 会员id
 * @property string $user 会员名
 * @property string $pwd 密码
 * @property string $encrypt 加密因子
 * @property string $name 会员昵称
 * @property string $realname 真实姓名
 * @property string $weixin 微信
 * @property string $mobile 手机号
 * @property int $register_time 注册时间
 * @property int $login_time 登录时间
 * @property int $quit_time 退出时间
 * @property string $register_ip 注册ip
 * @property string $last_ip 最后登录ip
 * @property int $login_num 用户登录次数
 * @property string $email 邮箱号
 * @property string $address 用户地址
 * @property string $address2 详细地址
 * @property int $groupid 用户所在组
 * @property int $lock 是否屏蔽
 * @property int $have_message 是否有推送消息
 * @property int $status 审核状态
 * @property string $sex 性别
 * @property string $head_portrait_small 会员小头像
 * @property string $head_portrait_big 会员大头像
 * @property string $qq_id qqKEY
 * @property string $weibo_id weiboKEY
 * @property int $forbid 用户是否被禁止评论，1-禁止，0-未禁止
 * @property string $forbid_time 操作禁止评论的时间
 * @property string $forbid_keeptime 禁止的时长
 * @property int $siteid 站点id
 * @property string $power 用户权限
 * @property int $costtime 有效开始时间
 * @property int $costendtime 有效截止时间
 * @property string $comp 公司名称
 * @property string $invoice 发票抬头
 * @property string $dutynumbe 税号
 * @property string $creditnumbe 社会信用代码
 * @property string $remarks 备注
 * @property string $unionid 微信唯一标识unionid
 *
 * @property XpMemberGroup $group
 */
class XpMember extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'xp_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['register_time', 'login_time', 'quit_time', 'login_num', 'groupid', 'lock', 'have_message', 'status', 'forbid', 'siteid', 'costtime', 'costendtime'], 'integer'],
            [['sex'], 'string'],
            [['user'], 'string', 'max' => 25],
            [['pwd'], 'string', 'max' => 32],
            [['encrypt'], 'string', 'max' => 6],
            [['name', 'realname', 'email'], 'string', 'max' => 30],
            [['weixin', 'qq_id', 'weibo_id', 'comp', 'invoice', 'dutynumbe', 'creditnumbe', 'remarks'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 11],
            [['register_ip', 'last_ip', 'forbid_time'], 'string', 'max' => 15],
            [['address', 'unionid'], 'string', 'max' => 50],
            [['address2'], 'string', 'max' => 250],
            [['head_portrait_small', 'head_portrait_big'], 'string', 'max' => 500],
            [['forbid_keeptime'], 'string', 'max' => 10],
            [['power'], 'string', 'max' => 255],
            [['groupid'], 'exist', 'skipOnError' => true, 'targetClass' => XpMemberGroup::className(), 'targetAttribute' => ['groupid' => 'group_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '会员id'),
            'user' => Yii::t('app', '会员名'),
            'pwd' => Yii::t('app', '密码'),
            'encrypt' => Yii::t('app', '加密因子'),
            'name' => Yii::t('app', '会员昵称'),
            'realname' => Yii::t('app', '真实姓名'),
            'weixin' => Yii::t('app', '微信'),
            'mobile' => Yii::t('app', '手机号'),
            'register_time' => Yii::t('app', '注册时间'),
            'login_time' => Yii::t('app', '登录时间'),
            'quit_time' => Yii::t('app', '退出时间'),
            'register_ip' => Yii::t('app', '注册ip'),
            'last_ip' => Yii::t('app', '最后登录ip'),
            'login_num' => Yii::t('app', '用户登录次数'),
            'email' => Yii::t('app', '邮箱号'),
            'address' => Yii::t('app', '用户地址'),
            'address2' => Yii::t('app', '详细地址'),
            'groupid' => Yii::t('app', '用户所在组'),
            'lock' => Yii::t('app', '是否屏蔽'),
            'have_message' => Yii::t('app', '是否有推送消息'),
            'status' => Yii::t('app', '审核状态'),
            'sex' => Yii::t('app', '性别'),
            'head_portrait_small' => Yii::t('app', '会员小头像'),
            'head_portrait_big' => Yii::t('app', '会员大头像'),
            'qq_id' => Yii::t('app', 'qqKEY'),
            'weibo_id' => Yii::t('app', 'weiboKEY'),
            'forbid' => Yii::t('app', '用户是否被禁止评论，1-禁止，0-未禁止'),
            'forbid_time' => Yii::t('app', '操作禁止评论的时间'),
            'forbid_keeptime' => Yii::t('app', '禁止的时长'),
            'siteid' => Yii::t('app', '站点id'),
            'power' => Yii::t('app', '用户权限'),
            'costtime' => Yii::t('app', '有效开始时间'),
            'costendtime' => Yii::t('app', '有效截止时间'),
            'comp' => Yii::t('app', '公司名称'),
            'invoice' => Yii::t('app', '发票抬头'),
            'dutynumbe' => Yii::t('app', '税号'),
            'creditnumbe' => Yii::t('app', '社会信用代码'),
            'remarks' => Yii::t('app', '备注'),
            'unionid' => Yii::t('app', '微信唯一标识unionid'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(XpMemberGroup::className(), ['group_id' => 'groupid']);
    }

    /**
     * {@inheritdoc}
     * @return XpMemberQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XpMemberQuery(get_called_class());
    }
}
