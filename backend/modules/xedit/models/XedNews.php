<?php

namespace backend\modules\xedit\models;

use common\models\AdminDep;
use common\models\AdminTeam;
use common\models\auth\AuthItem;
use common\utils\ToolUtil;
use phpDocumentor\Reflection\Types\Self_;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%xed_news}}".
 *
 * @property string $id 主键
 * @property string $year xml数据实际年份 [旧表同步]
 * @property string $month xml数据实际月份 [旧表同步]
 * @property string $paper_id 报纸ID
 * @property string $paper_page_id 报纸版次ID
 * @property string $paper_issue_id 报纸期次ID
 * @property int $t_date 投稿日期
 * @property int $up_date 修改时间
 * @property string $title_eyebrow 引题
 * @property string $title 标题
 * @property string $title_sub 副题
 * @property int $user_id 关联admin表主键 用户标识
 * @property string $author 作者名称，投稿可为空
 * @property string $foreword 新闻引言
 * @property string $content 正文
 * @property int $fromtype 稿件来源 关联xed_new_fromtype表id
 * @property int $ifpass 审核进程状态：1:等待审核；2:未通过审核；3:一审通过审核；4:二审通过；5：终审通过
 * @property int $newstype_id 稿件种类id 关联xed_new_type的id
 * @property int $status 编辑状态 1：草稿；2：投稿；3:待修稿；4退稿
 * @property int $dep_id 所属部门id 关联admin_dep表id
 * @property int $team_id 所属部门id 关联admin_team表id
 * @property int $unit_id 所属单位id 关联admin_unit表id
 * @property string $sited 站点id
 * @property string $imgs 稿件多图
 * @property string $column_id 栏目id
 * @property string $channel_id 频道id
 * @property int $is_take 是否取搞 0 否 1是
 */
class XedNews extends \common\models\BaseModel
{

    const NEW_STATUS_YES = 5; //完结终审
    const STATUS_DEL = -1; //删除
    const SCENARIO_ADD = 'add';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xed_news}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paper_id', 'paper_page_id', 'paper_issue_id', 't_date', 'up_date', 'user_id', 'fromtype', 'ifpass', 'newstype_id', 'status', 'sited', 'column_id', 'channel_id', 'is_take'], 'integer'],
            [['title', 'content', 'fromtype', 'newstype_id'], 'required','message' => "{attribute}不能为空"],
            [['foreword', 'content', 'imgs'], 'string'],
            [['year'], 'string', 'max' => 4],
            [['month'], 'string', 'max' => 2],
            [['title_eyebrow', 'title', 'title_sub'], 'string', 'max' => 100],
            [['author'], 'string', 'max' => 20],
        ];
    }

    //场景
    public function scenarios()
    {
        return [
            self::SCENARIO_ADD => [
                'title_eyebrow', 'title', 'title_sub','author','content','t_date', 'up_date', 'user_id', 'fromtype', 'ifpass', 'newstype_id', 'status','foreword'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 't_date',// 自己根据数据库字段修改
                'updatedAtAttribute' => 'up_date',
//                'value'   => new Expression('NOW()'),
                'value'   => function(){return time();},
            ],
        ];
    }


        /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'year' => 'xml数据实际年份 [旧表同步]',
            'month' => 'xml数据实际月份 [旧表同步]',
            'paper_id' => '报纸ID',
            'paper_page_id' => '报纸版次ID',
            'paper_issue_id' => '报纸期次ID',
            't_date' => '投稿日期',
            'up_date' => '修改时间',
            'title_eyebrow' => '引题',
            'title' => '标题',
            'title_sub' => '副题',
            'user_id' => '关联admin表主键 用户标识',
            'author' => '作者名称，投稿可为空',
            'foreword' => '新闻引言',
            'content' => '稿件内容',
            'fromtype' => '稿件来源 关联xed_new_fromtype表id',
            'ifpass' => '审核进程状态：1:等待审核；2:未通过审核；3:一审通过审核；4:二审通过；5：终审通过',
            'newstype_id' => '稿件种类id 关联xed_new_type的id',
            'status' => '编辑状态 1：草稿；2：投稿；3:待修稿；4退稿',
            'sited' => '站点id',
            'imgs' => '稿件多图',
            'column_id' => '栏目id',
            'channel_id' => '频道id',
            'is_take' => '是否取搞 0 否 1是',
        ];
    }

    public function getStatus(){
        //1:等待审核；2:未通过审核；3:一审通过审核；4:二审通过；5：终审通过；6：录用
        return [
            1 => '等待审核',
            2 => '未通过审核',
            3 => '一审通过审核',
            4 => '二审通过',
            5 => '终审通过'
        ];
    }

    /**
     * @Function 稿件种类
     * @Author Weihuaadmin@163.com
     * @return array
     */
    public static function getType(){
        return ArrayHelper::map(XedNewsType::findAllByWhere(['status' => 1],['id','type']),'id','type');
    }

    /**
     * @Function 直接添加稿件
     * @Author Weihuaadmin@163.com
     */
    public function addNew($postData){
        $this->scenario = self::SCENARIO_ADD;
        $insertData = [
            'ifpass' => self::NEW_STATUS_YES,
            'status' => 2,
            'fromtype' => XedNewsFromtype::FROM_TYPE_ADD,
            'user_id' => $this->GetUserId()
        ];
        $postData['new'] = ArrayHelper::merge($postData['new'],$insertData);
        $postData['new']['content'] = $postData['content'];
        $dep_id = $postData['new']['dep_id'];
        $team_id = $postData['new']['team_id'];
        $id = $postData['id'];
        if($this->load($postData,'new') && $this->validate()){
            if($id){
                $this->setOldAttribute('id',$id);
            }
            $transaction = \Yii::$app->db->beginTransaction();
            if($this->save()){
                $pk = empty($id) ? $this->getPrimaryKey() : $id;
                //更新所属关系
                XedNewsBelong::deleteAll("news_id ='{$pk}'");
                $belongModel = new XedNewsBelong();
                $_belongModel = clone $belongModel;
                $depData = [
                    'news_id' => $pk,
                    'out_id' => $dep_id,
                    'type' => AuthItem::DEP_TYPE,
                    'unit_id' => AdminDep::findValueByWhere(['depid' => $dep_id],['unit_id'],['depid'=>SORT_DESC])
                ];
                $teamData = [
                    'news_id' => $pk,
                    'out_id' => $team_id,
                    'type' => AuthItem::TEAM_TYPE,
                    'unit_id' => AdminTeam::findValueByWhere(['teamid' => $team_id],['unit_id'],['teamid'=>SORT_DESC])
                ];
                $res = $belongModel->addBelong($depData);
                $res2 = $_belongModel->addBelong($teamData);
                if($res && $res2){
                    $transaction->commit();
                    return ToolUtil::returnAjaxMsg(true,'操作成功');
                }
            }
            $transaction->rollBack();
            return ToolUtil::returnAjaxMsg(false,'操作失败');
        }
        return ToolUtil::returnAjaxMsg(false,$this->getModelError());
    }

    /**
     * @Function 删除新闻
     * @Author Weihuaadmin@163.com
     */
    public static function delNews($ids){
        $res = self::updateAll(['status' => self::STATUS_DEL],"id =:id",[':id' => $ids]);
        if($res){
            return ToolUtil::returnAjaxMsg(true,'删除成功');
        }
        return ToolUtil::returnAjaxMsg(false,'删除失败');
    }

    /**
     * @Function 取稿
     * 取稿 添加到取稿表 并且 稿件取稿次数增加
     * @Author Weihuaadmin@163.com
     * @param $id
     */
    public static function getNews($id,$mergeData){
        $toNewsModel = new XedToBeDistributedNews();
        $toAttrs = $toNewsModel -> getAttributes();
        $newsObj = self::findOne($id);
        $newsInfo = $newsObj -> getAttributes();
        $insertData = $mergeData;
        array_filter(array_keys($toAttrs),function ($n) use($newsInfo,&$insertData){
            unset($newsInfo['status']);
            $columnVal = ArrayHelper::getValue($newsInfo,$n);
            if(!empty($columnVal)){
                $insertData[$n] = $columnVal;
            }
        });
        $res = $toNewsModel->insertData($insertData);
        if($res){
            return ToolUtil::returnMsg(true);
        }else{
            return ToolUtil::returnMsg(false,'取搞失败');
        }
    }

}
