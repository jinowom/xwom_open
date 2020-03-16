<?php
/**
 * This is the model class for table "XportalNews";
 * @package backend\modules\xportal\models;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-09 20:50 */
namespace backend\modules\xportal\models;

use Yii;

/**
 * This is the model class for table "{{%xportal_news}}".
 *
 * @property int $id 文章id
 * @property int $catid 栏目id
 * @property int $channelid 频道id
 * @property string $title_eyebrow 引题（肩题）
 * @property string $title 标题
 * @property string $title_sub 副题
 * @property string $content 文章内容
 * @property string $author 作者
 * @property string $keywords 关键字
 * @property int $listorder 排序
 * @property string $groupids_view 阅读权限
 * @property int $news_checkserche 是否全文检索 0 --不,1--是
 * @property string $news_uploadfile 附件
 * @property string $news_movie_uir 视频
 * @property string $movie_blankurl 视频外联地址
 * @property int $paging_type 分页类型 1=字符数 2= 图片个数
 * @property int $maxcharperpage 每页最大字符
 * @property int $maxcharimge 每页最大图片个数
 * @property string $relation 相关文章
 * @property int $allow_comment 是否允许评论，0不允许，1允许
 * @property string $copyfrom 来源
 * @property int $status 状态：0（未审核），1=已审（划分栏目）2=确认发布 3=退稿
 * @property string $islink 外部跳转链接
 * @property int $yes_no_islink 是否启用外部链接0为NO,1为YES
 * @property string $username 添加-用户名
 * @property int $click_number 点击数
 * @property int $inputime 添加时间
 * @property int $updatetime 更新时间
 * @property int $index_listorder 首页排行排序
 * @property int $channel_listorder 频道排行排序
 * @property int $is_image 是否有图片1有0没有
 * @property string $arrparent_catid 分发栏目id
 * @property string $update_username 修改-用户名
 * @property string $rejection_reason 退稿理由
 * @property string $use_catid 分发被使用的栏目id
 * @property int $cache 缓存依赖
 * @property int $ranking_position 推荐版位名称
 * @property int $news_author_id 管理员id用于统计工作量
 * @property string $shuffling 轮播图
 * @property string $thumbnail 缩略图
 * @property int $shuffling_index 轮播首页推荐1为推荐0为不推荐
 * @property int $shuffling_channel 轮播频道推荐1为推荐0为不推荐
 * @property string $news_uploadfile_describe 新闻附件描述
 * @property string $news_movie_uir_describe 新闻视频描述
 * @property int $news_discuss_num
 * @property int $siteid 站点id
 */
class XportalNews extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xportal_news}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['catid', 'channelid', 'listorder', 'news_checkserche', 'paging_type', 'maxcharperpage', 'maxcharimge', 'allow_comment', 'status', 'yes_no_islink', 'click_number', 'inputime', 'updatetime', 'index_listorder', 'channel_listorder', 'is_image', 'cache', 'ranking_position', 'news_author_id', 'shuffling_index', 'shuffling_channel', 'news_discuss_num', 'siteid'], 'integer'],
            [['content', 'movie_blankurl'], 'required'],
            [['content'], 'string'],
            [['title_eyebrow', 'title', 'title_sub', 'author', 'keywords'], 'string', 'max' => 150],
            [['groupids_view', 'news_uploadfile_describe', 'news_movie_uir_describe'], 'string', 'max' => 100],
            [['news_uploadfile', 'news_movie_uir', 'relation', 'copyfrom', 'shuffling', 'thumbnail'], 'string', 'max' => 255],
            [['movie_blankurl', 'islink', 'arrparent_catid', 'rejection_reason', 'use_catid'], 'string', 'max' => 500],
            [['username', 'update_username'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '文章id'),
            'catid' => Yii::t('app', '栏目id'),
            'channelid' => Yii::t('app', '频道id'),
            'title_eyebrow' => Yii::t('app', '引题（肩题）'),
            'title' => Yii::t('app', '标题'),
            'title_sub' => Yii::t('app', '副题'),
            'content' => Yii::t('app', '文章内容'),
            'author' => Yii::t('app', '作者'),
            'keywords' => Yii::t('app', '关键字'),
            'listorder' => Yii::t('app', '排序'),
            'groupids_view' => Yii::t('app', '阅读权限'),
            'news_checkserche' => Yii::t('app', '是否全文检索 0 --不,1--是'),
            'news_uploadfile' => Yii::t('app', '附件'),
            'news_movie_uir' => Yii::t('app', '视频'),
            'movie_blankurl' => Yii::t('app', '视频外联地址'),
            'paging_type' => Yii::t('app', '分页类型 1=字符数 2= 图片个数'),
            'maxcharperpage' => Yii::t('app', '每页最大字符'),
            'maxcharimge' => Yii::t('app', '每页最大图片个数'),
            'relation' => Yii::t('app', '相关文章'),
            'allow_comment' => Yii::t('app', '是否允许评论，0不允许，1允许'),
            'copyfrom' => Yii::t('app', '来源'),
            'status' => Yii::t('app', '状态：0（未审核），1=已审（划分栏目）2=确认发布 3=退稿'),
            'islink' => Yii::t('app', '外部跳转链接'),
            'yes_no_islink' => Yii::t('app', '是否启用外部链接0为NO,1为YES'),
            'username' => Yii::t('app', '添加-用户名'),
            'click_number' => Yii::t('app', '点击数'),
            'inputime' => Yii::t('app', '添加时间'),
            'updatetime' => Yii::t('app', '更新时间'),
            'index_listorder' => Yii::t('app', '首页排行排序'),
            'channel_listorder' => Yii::t('app', '频道排行排序'),
            'is_image' => Yii::t('app', '是否有图片1有0没有'),
            'arrparent_catid' => Yii::t('app', '分发栏目id'),
            'update_username' => Yii::t('app', '修改-用户名'),
            'rejection_reason' => Yii::t('app', '退稿理由'),
            'use_catid' => Yii::t('app', '分发被使用的栏目id'),
            'cache' => Yii::t('app', '缓存依赖'),
            'ranking_position' => Yii::t('app', '推荐版位名称'),
            'news_author_id' => Yii::t('app', '管理员id用于统计工作量'),
            'shuffling' => Yii::t('app', '轮播图'),
            'thumbnail' => Yii::t('app', '缩略图'),
            'shuffling_index' => Yii::t('app', '轮播首页推荐1为推荐0为不推荐'),
            'shuffling_channel' => Yii::t('app', '轮播频道推荐1为推荐0为不推荐'),
            'news_uploadfile_describe' => Yii::t('app', '新闻附件描述'),
            'news_movie_uir_describe' => Yii::t('app', '新闻视频描述'),
            'news_discuss_num' => Yii::t('app', 'News Discuss Num'),
            'siteid' => Yii::t('app', '站点id'),
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
     * @return XportalNewsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new XportalNewsQuery(get_called_class());
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
