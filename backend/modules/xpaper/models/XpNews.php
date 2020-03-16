<?php

namespace backend\modules\xpaper\models;

use Yii;

/**
 * This is the model class for table "xp_news".
 *
 * @property int $id 新闻id
 * @property int $order_num 排序id
 * @property int $special_order 专题新闻显示排序
 * @property string $special_id 专题(题材）id
 * @property int $emphasis_order 重点推荐显示排序
 * @property int $emphasis 是否推荐显示，0不推荐 1推荐
 * @property string $title_eyebrow 引题（肩题）
 * @property string $title 标题
 * @property string $title_sub 副题
 * @property string $author 作者
 * @property string $class 分类
 * @property string $articleclass 文章分类
 * @property int $imagesnumbers 新闻插图数量
 * @property string $resource 文章来源
 * @property string $foreword 新闻前言或引言
 * @property string $keywords 关键词
 * @property string $content 正文
 * @property int $type 文章类型：1-无图文章  2-有图文章  3-纯图片文章
 * @property string $created_at 添加时间
 * @property string $updated_at 更新时间
 * @property int $paper_id 所属版面id
 * @property int $release_id 所属期次id
 * @property int $site_id 所属站点
 * @property int $checkserche 是否全文检索 0--不 1--是
 * @property string $uploadfile 附件
 * @property string $movie_uir 视频
 * @property int $paging_type 分页类型 1=字符数 2= 图片个数
 * @property int $maxcharperpage 每页最大字符
 * @property int $maxcharimge 每页最大图片个数
 * @property int $searchid 全站搜索ID
 * @property int $yes_no_islink 是否启用外部链接 0不启用 1启用
 * @property string $islink 外部链接
 * @property int $isdata 是否用附表数据
 * @property string $coordinate 原始坐标
 * @property string $canvas_type 坐标对象类型
 * @property int $paper_order 版次排序
 * @property int $animation_takeaway 动画导读是否 1是 0否
 * @property int $cache 缓存依赖
 * @property int $click_number 浏览点击数
 * @property int $like_number 浏览点击数
 * @property int $status 状态
 */
class XpNews extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'xp_news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_num', 'special_order', 'emphasis_order', 'emphasis', 'imagesnumbers', 'type', 'paper_id', 'release_id', 'site_id', 'checkserche', 'paging_type', 'maxcharperpage', 'maxcharimge', 'searchid', 'yes_no_islink', 'isdata', 'paper_order', 'animation_takeaway', 'cache', 'click_number', 'like_number', 'status'], 'integer'],
            [['content'], 'string'],
            [['special_id'], 'string', 'max' => 5000],
            [['title_eyebrow', 'title', 'title_sub'], 'string', 'max' => 200],
            [['author', 'class', 'articleclass', 'resource', 'keywords'], 'string', 'max' => 50],
            [['foreword'], 'string', 'max' => 2000],
            [['created_at', 'updated_at'], 'string', 'max' => 13],
            [['uploadfile', 'movie_uir'], 'string', 'max' => 500],
            [['islink'], 'string', 'max' => 160],
            [['coordinate', 'canvas_type'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '新闻id'),
            'order_num' => Yii::t('app', '排序id'),
            'special_order' => Yii::t('app', '专题新闻显示排序'),
            'special_id' => Yii::t('app', '专题(题材）id'),
            'emphasis_order' => Yii::t('app', '重点推荐显示排序'),
            'emphasis' => Yii::t('app', '是否推荐显示，0不推荐 1推荐'),
            'title_eyebrow' => Yii::t('app', '引题（肩题）'),
            'title' => Yii::t('app', '标题'),
            'title_sub' => Yii::t('app', '副题'),
            'author' => Yii::t('app', '作者'),
            'class' => Yii::t('app', '分类'),
            'articleclass' => Yii::t('app', '文章分类'),
            'imagesnumbers' => Yii::t('app', '新闻插图数量'),
            'resource' => Yii::t('app', '文章来源'),
            'foreword' => Yii::t('app', '新闻前言或引言'),
            'keywords' => Yii::t('app', '关键词'),
            'content' => Yii::t('app', '正文'),
            'type' => Yii::t('app', '文章类型：1-无图文章  2-有图文章  3-纯图片文章'),
            'created_at' => Yii::t('app', '添加时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'paper_id' => Yii::t('app', '所属版面id'),
            'release_id' => Yii::t('app', '所属期次id'),
            'site_id' => Yii::t('app', '所属站点'),
            'checkserche' => Yii::t('app', '是否全文检索 0--不 1--是'),
            'uploadfile' => Yii::t('app', '附件'),
            'movie_uir' => Yii::t('app', '视频'),
            'paging_type' => Yii::t('app', '分页类型 1=字符数 2= 图片个数'),
            'maxcharperpage' => Yii::t('app', '每页最大字符'),
            'maxcharimge' => Yii::t('app', '每页最大图片个数'),
            'searchid' => Yii::t('app', '全站搜索ID'),
            'yes_no_islink' => Yii::t('app', '是否启用外部链接 0不启用 1启用'),
            'islink' => Yii::t('app', '外部链接'),
            'isdata' => Yii::t('app', '是否用附表数据'),
            'coordinate' => Yii::t('app', '原始坐标'),
            'canvas_type' => Yii::t('app', '坐标对象类型'),
            'paper_order' => Yii::t('app', '版次排序'),
            'animation_takeaway' => Yii::t('app', '动画导读是否 1是 0否'),
            'cache' => Yii::t('app', '缓存依赖'),
            'click_number' => Yii::t('app', '浏览点击数'),
            'like_number' => Yii::t('app', '浏览点击数'),
            'status' => Yii::t('app', '状态'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return XpNewsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XpNewsQuery(get_called_class());
    }
}
