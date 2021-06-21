<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%site_base}}".
 *
 * @property string $siteid 站点ID
 * @property string $name 站点名
 * @property string $dirname 站点目录
 * @property string $domain 域名
 * @property string $serveralias 域名别名，多个以“,”分割
 * @property string $keywords SEO关键词
 * @property string $description 站点描述
 * @property string $site_point 发布点
 * @property int $smarty_id 使用的模板ID
 * @property int $smarty_app_id 移动端模板ID
 * @property string $createtime 移动端模板ID
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
 * @property int $province 用户所在国家--目前默认中国=1
 * @property int $city 市/县---参见【city】表
 * @property int $industry 行业---参见【industry】表
 * @property int $cache 站点首页缓存
 * @property string $basemail 邮箱名，用于发送邮件
 * @property string $mailpwd 邮箱密码，用户发送邮件
 * @property int $status 0代表停止,1代表运行中
 * @property int $site_open 开放展示字段1默认展示0不展示
 * @property string $record 备案号
 */
class SiteBase extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%site_base}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['domain', 'serveralias', 'keywords', 'description'], 'required'],
            [['smarty_id', 'smarty_app_id', 'client_country', 'province', 'city', 'industry', 'cache', 'status', 'site_open'], 'integer'],
            [['address', 'logo', 'banner'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['dirname'], 'string', 'max' => 200],
            [['domain', 'serveralias', 'ftp_folder', 'auto_folder'], 'string', 'max' => 100],
            [['keywords', 'description'], 'string', 'max' => 255],
            [['site_point', 'zipcode', 'tel', 'fax', 'email', 'copyright', 'reg_time', 'record'], 'string', 'max' => 50],
            [['createtime', 'begin_time', 'end_time'], 'string', 'max' => 15],
            [['basemail', 'mailpwd'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'siteid' => '站点ID',
            'name' => '站点名',
            'dirname' => '站点目录',
            'domain' => '域名',
            'serveralias' => '域名别名，多个以“,”分割',
            'keywords' => 'SEO关键词',
            'description' => '站点描述',
            'site_point' => '发布点',
            'smarty_id' => '使用的模板ID',
            'smarty_app_id' => '移动端模板ID',
            'createtime' => '移动端模板ID',
            'address' => '站点主管单位地址',
            'zipcode' => '站点主管单位邮编',
            'tel' => '站点主管单位电话',
            'fax' => '传真',
            'email' => '站点主管单位负责人邮箱',
            'copyright' => '站点主管单位',
            'logo' => '站点Logo',
            'banner' => '站点banner',
            'reg_time' => '注册时间',
            'ftp_folder' => '素材上传路径',
            'auto_folder' => '自动导入xml数据文件路径',
            'begin_time' => '使用开始时间',
            'end_time' => '使用结束时间，当前时间大于结束时间，status字段值0；如果为空则永久',
            'client_country' => '用户所在国家--目前默认中国=1',
            'province' => '用户所在国家--目前默认中国=1',
            'city' => '市/县---参见【city】表',
            'industry' => '行业---参见【industry】表',
            'cache' => '站点首页缓存',
            'basemail' => '邮箱名，用于发送邮件',
            'mailpwd' => '邮箱密码，用户发送邮件',
            'status' => '0代表停止,1代表运行中',
            'site_open' => '开放展示字段1默认展示0不展示',
            'record' => '备案号',
        ];
    }
}
