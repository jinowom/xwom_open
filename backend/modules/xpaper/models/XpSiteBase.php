<?php

namespace backend\modules\xpaper\models;

use Yii;

/**
 * This is the model class for table "xp_site_base".
 *
 * @property int $siteid 站点ID
 * @property int $appid 应用ID
 * @property string $name 站点名
 * @property string $dirname 站点目录
 * @property string $domain 域名
 * @property string $serveralias 域名别名，多个以“,”分割
 * @property string $keywords SEO关键词
 * @property string $description 站点描述
 * @property string $site_point 发布点
 * @property int $smarty_id 使用的模板ID
 * @property int $smarty_app_id 移动端模板ID
 * @property string $address 站点主管单位地址
 * @property string $zipcode 站点主管单位邮编
 * @property string $tel 站点主管单位电话
 * @property string $fax 传真
 * @property string $email 站点主管单位负责人邮箱
 * @property string $copyright 站点主管单位
 * @property string $logo 站点Logo
 * @property string $banner 站点banner
 * @property string $reg_time 注册时间
 * @property string $ftp_folder 素材上传路径
 * @property string $auto_folder 自动导入xml数据文件路径
 * @property string $begin_time 使用开始时间
 * @property string $end_time 使用结束时间，当前时间大于结束时间，status字段值0；如果为空则永久
 * @property int $client_country 用户所在国家--目前默认中国=1
 * @property int $province 省/自治区--参见【cms_province】 表
 * @property int $city 市/县---参见【city】表
 * @property int $industry 行业---参见【industry】表
 * @property int $cache 站点首页缓存
 * @property string $basemail 邮箱名，用于发送邮件
 * @property string $mailpwd 邮箱密码，用户发送邮件
 * @property int $status 运行状态：1代表运行中；0代表停止
 * @property int $site_open 开放展示字段  1默认展示；0不展示
 * @property string $record 备案号
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $is_show 是否逻辑删除 1:未删除 ，0:已删除
 * @property string $default_style 默认风格_主要指色调配色风格
 * @property int $islogin 开启会员认证
 * @property int $ismempower 开启会员权限
 * @property string $contacts 联系人
 * @property string $comp_invoice 公司账户
 * @property string $comp_bank 公司开户行
 * @property string $bank_numb 银行账号
 * @property int $company_id 主管单位id
 * @property int $paymode 0代表后付费,1代表包年，2包月，3季度
 */
class XpSiteBase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'xp_site_base';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['appid', 'domain', 'serveralias', 'keywords', 'description', 'is_show'], 'required'],
            [['appid', 'smarty_id', 'smarty_app_id', 'client_country', 'province', 'city', 'industry', 'cache', 'status', 'site_open', 'created_at', 'updated_at', 'is_show', 'islogin', 'ismempower', 'company_id', 'paymode'], 'integer'],
            [['address', 'logo', 'banner'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['dirname'], 'string', 'max' => 200],
            [['domain', 'serveralias', 'ftp_folder', 'auto_folder', 'comp_invoice', 'comp_bank'], 'string', 'max' => 100],
            [['keywords', 'description'], 'string', 'max' => 255],
            [['site_point', 'zipcode', 'tel', 'fax', 'email', 'copyright', 'reg_time', 'record', 'default_style'], 'string', 'max' => 50],
            [['begin_time', 'end_time'], 'string', 'max' => 15],
            [['basemail', 'mailpwd'], 'string', 'max' => 60],
            [['contacts'], 'string', 'max' => 20],
            [['bank_numb'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'siteid' => Yii::t('app', '站点ID'),
            'appid' => Yii::t('app', '应用ID'),
            'name' => Yii::t('app', '站点名'),
            'dirname' => Yii::t('app', '站点目录'),
            'domain' => Yii::t('app', '域名'),
            'serveralias' => Yii::t('app', '域名别名，多个以“,”分割'),
            'keywords' => Yii::t('app', 'SEO关键词'),
            'description' => Yii::t('app', '站点描述'),
            'site_point' => Yii::t('app', '发布点'),
            'smarty_id' => Yii::t('app', '使用的模板ID'),
            'smarty_app_id' => Yii::t('app', '移动端模板ID'),
            'address' => Yii::t('app', '站点主管单位地址'),
            'zipcode' => Yii::t('app', '站点主管单位邮编'),
            'tel' => Yii::t('app', '站点主管单位电话'),
            'fax' => Yii::t('app', '传真'),
            'email' => Yii::t('app', '站点主管单位负责人邮箱'),
            'copyright' => Yii::t('app', '站点主管单位'),
            'logo' => Yii::t('app', '站点Logo'),
            'banner' => Yii::t('app', '站点banner'),
            'reg_time' => Yii::t('app', '注册时间'),
            'ftp_folder' => Yii::t('app', '素材上传路径'),
            'auto_folder' => Yii::t('app', '自动导入xml数据文件路径'),
            'begin_time' => Yii::t('app', '使用开始时间'),
            'end_time' => Yii::t('app', '使用结束时间，当前时间大于结束时间，status字段值0；如果为空则永久'),
            'client_country' => Yii::t('app', '用户所在国家--目前默认中国=1'),
            'province' => Yii::t('app', '省/自治区--参见【cms_province】 表'),
            'city' => Yii::t('app', '市/县---参见【city】表'),
            'industry' => Yii::t('app', '行业---参见【industry】表'),
            'cache' => Yii::t('app', '站点首页缓存'),
            'basemail' => Yii::t('app', '邮箱名，用于发送邮件'),
            'mailpwd' => Yii::t('app', '邮箱密码，用户发送邮件'),
            'status' => Yii::t('app', '运行状态：1代表运行中；0代表停止'),
            'site_open' => Yii::t('app', '开放展示字段  1默认展示；0不展示'),
            'record' => Yii::t('app', '备案号'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'is_show' => Yii::t('app', '是否逻辑删除 1:未删除 ，0:已删除'),
            'default_style' => Yii::t('app', '默认风格_主要指色调配色风格'),
            'islogin' => Yii::t('app', '开启会员认证'),
            'ismempower' => Yii::t('app', '开启会员权限'),
            'contacts' => Yii::t('app', '联系人'),
            'comp_invoice' => Yii::t('app', '公司账户'),
            'comp_bank' => Yii::t('app', '公司开户行'),
            'bank_numb' => Yii::t('app', '银行账号'),
            'company_id' => Yii::t('app', '主管单位id'),
            'paymode' => Yii::t('app', '0代表后付费,1代表包年，2包月，3季度'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return XpSiteBaseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XpSiteBaseQuery(get_called_class());
    }
}
