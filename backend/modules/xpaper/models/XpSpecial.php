<?php

namespace backend\modules\xpaper\models;

use Yii;

/**
 * This is the model class for table "xp_special".
 *
 * @property int $id 专题id
 * @property int $siteid 站点id
 * @property string $title 专题名称
 * @property string $thumb 专题横幅缩略图
 * @property string $banner 专题横幅
 * @property string $description 简介
 * @property string $url 访问地址
 * @property int $ishtml 是否生成静态1:生成 0：不生成
 * @property int $ispage 是否分页1：分页 0：不分页
 * @property string $filename 专题静态页文件名
 * @property int $adminid 添加专题的用户id
 * @property int $userid 允许操作本专题用户ID
 * @property string $createtime 专题创建时间
 * @property int $listorder 排序
 * @property int $elite 是否推荐   1：推荐 0：不推荐
 * @property int $status 是否启用   1：不启用0：启用
 * @property int $cache 缓存依赖
 *
 * @property XpSiteBase $site
 */
class XpSpecial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'xp_special';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['siteid', 'ishtml', 'ispage', 'adminid', 'userid', 'listorder', 'elite', 'status', 'cache'], 'integer'],
            [['title', 'filename'], 'string', 'max' => 150],
            [['thumb', 'banner', 'url'], 'string', 'max' => 300],
            [['description'], 'string', 'max' => 500],
            [['createtime'], 'string', 'max' => 13],
            [['siteid'], 'exist', 'skipOnError' => true, 'targetClass' => XpSiteBase::className(), 'targetAttribute' => ['siteid' => 'siteid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '专题id'),
            'siteid' => Yii::t('app', '站点id'),
            'title' => Yii::t('app', '专题名称'),
            'thumb' => Yii::t('app', '专题横幅缩略图'),
            'banner' => Yii::t('app', '专题横幅'),
            'description' => Yii::t('app', '简介'),
            'url' => Yii::t('app', '访问地址'),
            'ishtml' => Yii::t('app', '是否生成静态1:生成 0：不生成'),
            'ispage' => Yii::t('app', '是否分页1：分页 0：不分页'),
            'filename' => Yii::t('app', '专题静态页文件名'),
            'adminid' => Yii::t('app', '添加专题的用户id'),
            'userid' => Yii::t('app', '允许操作本专题用户ID'),
            'createtime' => Yii::t('app', '专题创建时间'),
            'listorder' => Yii::t('app', '排序'),
            'elite' => Yii::t('app', '是否推荐   1：推荐 0：不推荐'),
            'status' => Yii::t('app', '是否启用   1：不启用0：启用'),
            'cache' => Yii::t('app', '缓存依赖'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(XpSiteBase::className(), ['siteid' => 'siteid']);
    }

    /**
     * {@inheritdoc}
     * @return XpSpecialQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XpSpecialQuery(get_called_class());
    }
}
