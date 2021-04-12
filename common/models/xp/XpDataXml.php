<?php
/**
 * This is the model class for table "XpDataXml";
 * @package common\models\xp;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-07-12 14:54 */
namespace common\models\xp;

use Yii;

/**
 * This is the model class for table "{{%xp_data_xml}}".
 *
 * @property int $xml_id id
 * @property int $release_id 期次id
 * @property int $paper_id 版次id
 * @property int $news_id 新闻id
 * @property string $xml_reportedperson 报道对象
 * @property int $xml_site 站点
 * @property int $xml_year xml数据实际【年】份 索引使用
 * @property int $xml_month xml数据实际【月】份 索引使用
 * @property string $xml_papername 报纸名称
 * @property string $xml_lssue 期次
 * @property string $xml_date 出版日期
 * @property int $xml_pagecount 本期报纸总页数
 * @property string $xml_editionnumber 当前xml的版次
 * @property string $xml_thumbnailimage
 * @property string $xml_pagename 版名
 * @property string $xml_pagepdf
 * @property int $xml_sizewidth 版面实际宽度
 * @property int $xml_sizeheight 版面实际高度
 * @property string $xml_title_eyebrow 引题（肩题）
 * @property string $xml_title 标题
 * @property string $xml_title_sub 副题
 * @property string $xml_author 作者
 * @property string $xml_class 分类
 * @property string $xml_articleclass
 * @property int $xml_imagesnumbers 新闻插图数量
 * @property string $xml_resource 文章来源
 * @property string $xml_area 地区
 * @property string $xml_foreword 新闻前言或引言
 * @property string $xml_keywords 关键词
 * @property string $xml_content 正文
 * @property string $xml_coordinate 原始坐标
 * @property int $xml_wordsnumber 文章字数
 * @property string $xml_personage 人物
 * @property string $xml_subjectmatter 题材
 * @property string $xml_product 广告产品
 * @property string $xml_color 广告颜色
 * @property string $xml_advertisers 广告主
 * @property string $xml_size 广告面积
 * @property string $xml_adervertisetype 广告类型
 * @property string $xml_data_url URL
 */
class XpDataXml extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xp_data_xml}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['release_id', 'paper_id', 'news_id', 'xml_site', 'xml_year', 'xml_month', 'xml_pagecount', 'xml_sizewidth', 'xml_sizeheight', 'xml_imagesnumbers', 'xml_wordsnumber'], 'integer'],
            [['xml_content'], 'string'],
            [['xml_reportedperson', 'xml_papername', 'xml_coordinate', 'xml_personage', 'xml_subjectmatter', 'xml_color', 'xml_advertisers', 'xml_size', 'xml_adervertisetype'], 'string', 'max' => 50],
            [['xml_lssue', 'xml_editionnumber', 'xml_author', 'xml_class', 'xml_articleclass', 'xml_resource', 'xml_area'], 'string', 'max' => 100],
            [['xml_date'], 'string', 'max' => 20],
            [['xml_thumbnailimage', 'xml_pagepdf'], 'string', 'max' => 400],
            [['xml_pagename', 'xml_keywords', 'xml_product', 'xml_data_url'], 'string', 'max' => 200],
            [['xml_title_eyebrow', 'xml_title', 'xml_title_sub'], 'string', 'max' => 300],
            [['xml_foreword'], 'string', 'max' => 2000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'xml_id' => Yii::t('app', 'id'),
            'release_id' => Yii::t('app', '期次id'),
            'paper_id' => Yii::t('app', '版次id'),
            'news_id' => Yii::t('app', '新闻id'),
            'xml_reportedperson' => Yii::t('app', '报道对象'),
            'xml_site' => Yii::t('app', '站点'),
            'xml_year' => Yii::t('app', 'xml数据实际【年】份 索引使用'),
            'xml_month' => Yii::t('app', 'xml数据实际【月】份 索引使用'),
            'xml_papername' => Yii::t('app', '报纸名称'),
            'xml_lssue' => Yii::t('app', '期次'),
            'xml_date' => Yii::t('app', '出版日期'),
            'xml_pagecount' => Yii::t('app', '本期报纸总页数'),
            'xml_editionnumber' => Yii::t('app', '当前xml的版次'),
            'xml_thumbnailimage' => Yii::t('app', 'Xml Thumbnailimage'),
            'xml_pagename' => Yii::t('app', '版名'),
            'xml_pagepdf' => Yii::t('app', 'Xml Pagepdf'),
            'xml_sizewidth' => Yii::t('app', '版面实际宽度'),
            'xml_sizeheight' => Yii::t('app', '版面实际高度'),
            'xml_title_eyebrow' => Yii::t('app', '引题（肩题）'),
            'xml_title' => Yii::t('app', '标题'),
            'xml_title_sub' => Yii::t('app', '副题'),
            'xml_author' => Yii::t('app', '作者'),
            'xml_class' => Yii::t('app', '分类'),
            'xml_articleclass' => Yii::t('app', 'Xml Articleclass'),
            'xml_imagesnumbers' => Yii::t('app', '新闻插图数量'),
            'xml_resource' => Yii::t('app', '文章来源'),
            'xml_area' => Yii::t('app', '地区'),
            'xml_foreword' => Yii::t('app', '新闻前言或引言'),
            'xml_keywords' => Yii::t('app', '关键词'),
            'xml_content' => Yii::t('app', '正文'),
            'xml_coordinate' => Yii::t('app', '原始坐标'),
            'xml_wordsnumber' => Yii::t('app', '文章字数'),
            'xml_personage' => Yii::t('app', '人物'),
            'xml_subjectmatter' => Yii::t('app', '题材'),
            'xml_product' => Yii::t('app', '广告产品'),
            'xml_color' => Yii::t('app', '广告颜色'),
            'xml_advertisers' => Yii::t('app', '广告主'),
            'xml_size' => Yii::t('app', '广告面积'),
            'xml_adervertisetype' => Yii::t('app', '广告类型'),
            'xml_data_url' => Yii::t('app', 'URL'),
        ];
    }
    
    /**
    *验证之前的处理.如果不需要，调试后请删除
    */
    /*
    public function beforeValidate()
    {
        if (!empty($this->start_at) && strpos($this->start_at, '-')) {
            $this->start_at = strtotime($this->start_at);
        }
        if (!empty($this->end_at) && strpos($this->end_at, '-')) {
            $this->end_at = strtotime($this->end_at);
        }

        return parent::beforeValidate();
    }
    */

    /**
     * {@inheritdoc}
     * @return XpDataXmlQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XpDataXmlQuery(get_called_class());
    }

    /**
     * beforeSave 存储数据库之前事件的实务的编排、注入；
     */ 
    public function beforeSave($insert)
    {
    	if(parent::beforeSave($insert))
    	{
                $admin_id = Yii::$app->user->getId();
    		if($insert)
    		{
                    $this->created_at = time();
                    $this->updated_at = time();
                    if($admin_id){
                       $this->created_id = $admin_id; 
                       $this->updated_id = $admin_id; 
                    }	
    		}
    		else 
    		{
                    $this->updated_at = time();
                    $this->updated_id= $admin_id;
    		}
    		return true;			
    	}
    	else 
    	{
    		return false;
    	}
    }
    /**
    * @var array 开关变量字段示例，如果已经开启，需要把字段赋值以数组形式列出
    */
   //public $switchValues = [
   //    'status' => [
   //        'on' => 1,
   //        'off' => 0,
   //    ],
   //    'emphasis' => [
   //        'on' => 1,
   //        'off' => 0,
   //    ],
   //   //也可以是非 1，0 譬如，如下
   //   'isRecommend' => [
   //     'on' => 10,
   //     'off' => 0,
   //   ],
   //];
    /*
    * afterSave 保存之后的事件  示例
    */
//    public function afterSave($insert, $changedAttributes)
//    {
//        parent::afterSave($insert, $changedAttributes);
//        if ($insert) {
//            // 插入新数据之后修改订单状态
//            Order::updateAll(['shipping_status' => Order::SHIPPING_STATUS1, 'shipping_at' => time()], ['trade_no' => $this->order_trade_no]);
//        }
//    }
    
    /*
    * beforeDelete 删除之前事件 编排 ；  afterDelete 删除之后的事件编排  示例
    */
    
//    public function beforeDelete()
//    {
//        parent::beforeDelete();
//        
//    }
    /**
    * 保证数据事务完成，否则回滚
    */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE | self::OP_DELETE
            // self::SCENARIO_DEFAULT => self::OP_INSERT
        ];
    }
}
