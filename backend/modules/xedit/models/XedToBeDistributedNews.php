<?php

namespace backend\modules\xedit\models;

use moxuandi\helpers\ArrayHelper;
use phpDocumentor\Reflection\Types\Self_;
use Yii;

/**
 * This is the model class for table "{{%xed_to_be_distributed_news}}".
 *
 * @property int $id id
 * @property int $type 采集方式 1为采集点采集 2 取稿
 * @property string $title_eyebrow 引题（肩题）
 * @property string $title 标题
 * @property string $title_sub 副题
 * @property int $catid 栏目id
 * @property string $content 文章内容
 * @property string $news_uploadfile 附件
 * @property string $islink 外部跳转链接
 * @property string $author 作者
 * @property string $keywords 关键字
 * @property int $collecttime 采集时间
 * @property string $xml_issue 期次
 * @property string $xml_editionnumber 当前版次
 * @property string $add_name 采集人名字
 * @property int $site_id 站点id
 * @property string $conturl 采集网址
 * @property int $status 取稿状态
 */
class XedToBeDistributedNews extends \common\models\BaseModel
{
    const TYPE_COLL = 1; //采集稿件
    const TYPE_NEWS = 2; // 报纸取稿
    const TYPE_SITE = 3; // 网站取稿

    const STATUS_SUCCEED = 1; //取稿成功,审核成功
    const STATUS_FAILED = 2; //取稿失败,审核失败
    const STATUS_ING = 0; //取稿中,审核中

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xed_to_be_distributed_news}}';
    }

    /**
     * @Function 获取类型
     * @Author Weihuaadmin@163.com
     * @return array
     */
    public static function getTypeName(){
        return [self::TYPE_NEWS => '报纸取稿', self::TYPE_SITE => '网站取稿'];
    }

    /**
     * @Function 获取取稿状态
     * @Author Weihuaadmin@163.com
     * @return array
     */
    public static function getStatusName(){
        return [self::STATUS_ING => '取稿审核中', self::STATUS_SUCCEED=> '取稿审核成功', self::STATUS_FAILED => '取稿审核失败'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'catid', 'collecttime', 'site_id', 'status', 'pid'], 'integer'],
            [['content','nid'], 'required'],
            [['content'], 'string'],
            [['title_eyebrow', 'title', 'title_sub', 'author'], 'string', 'max' => 150],
            [['news_uploadfile', 'conturl'], 'string', 'max' => 255],
            [['islink'], 'string', 'max' => 500],
            [['keywords'], 'string', 'max' => 40],
            [['xml_issue'], 'string', 'max' => 10],
            [['xml_editionnumber'], 'string', 'max' => 20],
            [['add_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'type' => '采集方式 1为采集点采集 2 取稿',
            'title_eyebrow' => '引题（肩题）',
            'title' => '标题',
            'title_sub' => '副题',
            'catid' => '栏目id',
            'content' => '文章内容',
            'news_uploadfile' => '附件',
            'islink' => '外部跳转链接',
            'author' => '作者',
            'keywords' => '关键字',
            'collecttime' => '采集时间',
            'xml_issue' => '期次',
            'xml_editionnumber' => '当前版次',
            'add_name' => '采集人名字',
            'site_id' => '站点id',
            'conturl' => '采集网址',
            'status' => '取搞状态',
            'nid' => '稿件标识',
            'pid' => '报纸标识',
        ];
    }

    /**
     * @Function 取稿
     * @param $newInfo
     * @Author Weihuaadmin@163.com
     */
    public function insertData($newInfo)
    {
        $_this = clone $this;
        if($_this->load($newInfo,'') && $_this->validate()){
            /*$_this->attachBehaviors([
                TaskAppusermapBehavior::className()
            ]);*/
            return $_this->save();
        }
        print_r($_this->getModelError());
        return false;
    }

    /**
     * @Function 取消稿件
     * @Author Weihuaadmin@163.com
     * @param $news_id 稿件ID
     * @param $paper_id 报纸ID
     * @param $page_id 版面ID
     * @param $app_id 应用ID 默认 1
     */
    public function cancelData($news_id,$paper_id,$page_id,$app_id = 1){
        $dataInfo = $this->find()->where(['news_id' => $news_id , 'pager_id' => $paper_id, 'page_id' => $page_id, 'app_id' => $app_id])->one();
        $dataInfo -> attachBehaviors([
            TaskAppusermapBehavior::className()
        ]);
        $adminId = \Yii::$app->admin->id;
        if($dataInfo['use_adminid'] != $adminId){
            $this->addError('admin_id',"此稿件已被使用、您不是取稿人不能执行取消稿件操作！");
            return false;
        }
        return $dataInfo -> delete();
    }

    /**
     * @Function 获取取稿用途数据
     * @param int $type 类型
     * @param int $id 自增ID
     * @author weihuaadmin@163.com
     */
    public static function getTypeData($type, $id){
        $return = [];
        if($type == self::TYPE_NEWS){
            $news = XedPaperPage::findAllByWhere(['paper_id'=>$id],['paper_issue','issue_name','page','page_name']);
            foreach ($news as $key =>$val){
                $return[$val['paper_issue'].'-'.$val['page']] = $val['paper_issue'].'期-'.$val['page'].'版';
            }
        }elseif ($type == self::TYPE_SITE){

        }
        return $return;
    }
}
