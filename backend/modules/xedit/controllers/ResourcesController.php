<?php
/**
 * Created by PhpStorm.
 * User: WANGWEIHUA
 * Date: 2019/11/26
 * Time: 20:44
 */

namespace backend\modules\xedit\controllers;
use backend\controllers\BaseController;
use backend\modules\xedit\models\XedNews;
use backend\modules\xedit\models\XedNewsBelong;
use backend\modules\xedit\models\XedPaper;
use backend\modules\xedit\models\XedToBeDistributedNews;
use common\models\AdminDep;
use common\models\AdminTeam;
use common\models\AdminUnit;
use common\models\auth\AdminAuthRelation;
use common\models\auth\AuthItem;
use common\utils\ToolUtil;
use yii\helpers\ArrayHelper;

class ResourcesController extends BaseController
{
    protected $except = [
        'xedit/resources/news-list',
        'xedit/resources/add-new',
        'xedit/resources/del-new',
        'xedit/resources/get-news',
    ];

    /**
     * @Function 资料稿库管理
     * @Author Weihuaadmin@163.com
     * @return string
     */
    public function actionNewsList(){
        return $this->render('newslist');
    }

    /**
     * @Function 获取资料稿库数据
     * @Author Weihuaadmin@163.com
     * @return array
     */
    public function actionGetNewsList(){
        $wd = $this->post('wd');
        $start = $this->post('start');
        $end = $this->post('end');
        $query = XedNews::find()->select([
            'id','author','title','up_date','t_date','content'
        ])->filterWhere(['AND',
            ['ifpass' => XedNews::NEW_STATUS_YES],
            ['like','title',$wd]
        ]);

        if(!empty($start)){
            $startTime = strtotime($start. " 00:00:00");
            $query->andWhere(['>=','t_date',$startTime]);
        }
        if(!empty($end)){
            $endTime = strtotime($end. " 23:59:59");
            $query->andWhere(['<=','t_date',$endTime]);
        }

        $this->sidx = 't_date';
        $dealFunction = function ($lists){
            foreach ($lists as $key => $list){
                $unitIds = XedNewsBelong::findAllByWhere(['news_id'=>$list['id']],'unit_id');
                $unitIds = ArrayHelper::getColumn($unitIds,'unit_id');
                $units = AdminUnit::findAllByWhere(['unitid' => $unitIds],['name'],['unitid'=>SORT_DESC]);
                $units = ArrayHelper::getColumn($units,'name');
                $units = implode('，',$units);
                $lists[$key]['unit_name'] = $units;
                $lists[$key]['content_length'] = self::wordCount($list['content']);
                $lists[$key]['t_date'] = ToolUtil::getDate($list['t_date'],'Y-m-d H:i:s');
            }
            return $lists;
        };
        return $this->getJqTableData($query,$dealFunction);
    }

    /**
     * @Function 添加资料稿件
     * @Author Weihuaadmin@163.com
     */
    public function actionAddNew(){
        $newId = $this->get('newId');
        $request = \Yii::$app->request;
        if($request->isPost){
            $postData = $this->post();
            $xedNews = new XedNews();
            return $xedNews->addNew($postData);
        }

        $model = new XedNews();
        $depId = $teamId = 0;
        if($newId){
            $model = XedNews::findOne($newId);
            $depId = XedNewsBelong::findValueByWhere(['news_id' => $newId,'type'=>AuthItem::DEP_TYPE],'out_id');
            $teamId = XedNewsBelong::findValueByWhere(['news_id' => $newId,'type'=>AuthItem::TEAM_TYPE],'out_id');
        }
        $types = XedNews::getType();
        if(self::IsSuperAdmin()){
            $deps = AdminDep::findAllByWhereObj(['is_del' => 0, 'd_status' => AdminDep::STATUS_ACTIVE],['depid','name','father_id','unit_id'],['depid'=>SORT_DESC]);
            $items = AdminTeam::findAllByWhereObj(['is_del' => 0, 't_status' => AdminDep::STATUS_ACTIVE],['teamid','name','father_id','unit_id'],['teamid'=>SORT_DESC]);
        }else{
            $deps = AdminDep::findAllByWhereObj(['depid' => $this->dep_id, 'is_del' => 0, 'd_status' => AdminDep::STATUS_ACTIVE],['depid','name','father_id','unit_id'],['depid'=>SORT_DESC]);
            $items = AdminTeam::findAllByWhereObj(['teamid' => $this->team_id, 'is_del' => 0, 't_status' => AdminDep::STATUS_ACTIVE],['teamid','name','father_id','unit_id'],['teamid'=>SORT_DESC]);
        }
        return $this->render('_newadd',[
            'depId' => $depId,
            'teamId' => $teamId,
            'model' => $model,
            'types' => $types,
            'deps' => $deps,
            'items' => $items,
        ]);
    }

    /**
     * @Function 删除新闻
     * @Author Weihuaadmin@163.com
     * @return \yii\web\Response
     */
    public function actionDelNew(){
        $ids = $this->post('ids');
        return XedNews::delNews($ids);
    }

    /**
     * @Function 取稿
     * @Author Weihuaadmin@163.com
     */
    public function actionGetNews(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $ids = $this->post('ids');
            $news = $this->post('new');
            $newType = $news['newstype_id'];
            $mergeData = ['type' => $news['newstype_id']];
            if($newType == XedToBeDistributedNews::TYPE_NEWS){
                $k = 'ztype'.$news['newstype_id'];
                $pageIssue =  explode('-',$news[$k]);
                $mergeData['xml_issue'] = ToolUtil::getIsset($pageIssue[0],'');
                $mergeData['xml_editionnumber'] = ToolUtil::getIsset($pageIssue[1],'');
                $mergeData['status'] = XedToBeDistributedNews::STATUS_ING;
                $mergeData['collecttime'] = time();
                $kType = 'type'.$news['newstype_id'];
                $mergeData['pid'] = $news[$kType];
            }
            $failed = [];
            foreach ($ids as $key => $id){
                $mergeData['nid'] = $id;
                $doRes = XedNews::getNews($id,$mergeData);
                if($doRes['status'] == false){
                    $failed[] = "稿件{$id}取搞失败";
                    continue;
                }
            }
            $return = ToolUtil::returnAjaxMsg(true,'全部取稿成功');
            if(!empty($failed)){
                $errorMsg = implode(',',$failed);
                $return = ToolUtil::returnAjaxMsg(false,$errorMsg);
            }
            return $return;
        }
        $typeData = [
            2 => XedPaper::getPapers(),
            3 => []
        ];
        $ids = $this->get('ids',null);
        $ids = !empty($ids) ? explode(',',$ids) : [];
        $newsData = XedNews::findAllByWhere(['id' => $ids],['title','id']);
        if($newsData){
            $newsData = ArrayHelper::map($newsData,'id','title');
        }
        return $this->render('_getNews',[
            'typeNames' => XedToBeDistributedNews::getTypeName(),
            'typeData' => $typeData,
            'newsData' => $newsData
        ]);
    }

    /**
     * @Function 获取取搞用途数据
     * @Author Weihuaadmin@163.com
     */
    public function actionGetNewsTypeData(){
        $type = $this->post('type');
        $id = $this->post('val');
        return $this->asJson(XedToBeDistributedNews::getTypeData($type,$id));
    }

    public function actionGetNewsRecords(){
        $nid = $this->post('id');
        $xedNews = XedToBeDistributedNews::findAllByWhere([ 'nid'=> $nid],['pid','type','collecttime','xml_issue','xml_editionnumber','status']);
        foreach ($xedNews as $key => $news){
            $xedNews[$key]['typeName'] = ToolUtil::getSelectType(XedToBeDistributedNews::getTypeName(),$news['type']);
            $xedNews[$key]['name'] = XedPaper::findValueByWhere(['id' => $news['pid']],'paper_name');
            $xedNews[$key]['collecttime'] = ToolUtil::getDate($news['collecttime']);
            $xedNews[$key]['statusName'] = ToolUtil::getSelectType(XedToBeDistributedNews::getStatusName(),$news['status']);
        }
        return $this->asJson($xedNews);
    }
}