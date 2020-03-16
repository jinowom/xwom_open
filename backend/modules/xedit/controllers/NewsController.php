<?php

namespace backend\modules\xedit\controllers;

use backend\controllers\BaseController;
use backend\modules\xedit\models\XedNews;
use backend\modules\xedit\models\XedNewsBelong;
use backend\modules\xedit\models\XedNewsType;
use common\models\AdminUnit;
use common\utils\ToolUtil;
use moxuandi\helpers\ArrayHelper;

/**
 * Class NewController
 * @package backend\modules\xedit\controllers
 */
class NewsController extends BaseController
{
    /**
     * @Function 资源稿库-专题类
     * @Author Weihuaadmin@163.com
     * @return string
     */
    public function actionSpecial(){
        $title = \Yii::$app->params['name'].'-资源稿库-专题库';
        $type = XedNewsType::SPECIAL;
        return $this->render('special',[
            'title' => $title,
            'type' => $type
        ]);
    }

    /**
     * @Function 资源稿库-音频库
     * @Author Weihuaadmin@163.com
     * @return string
     */
    public function actionAudio(){
        $title = \Yii::$app->params['name'].'-资源稿库-音频库';
        $type = XedNewsType::AUDIO;
        return $this->render('special',[
            'title' => $title,
            'type' => $type
        ]);
    }

    /**
 * @Function 资源稿库-视频库
 * @Author Weihuaadmin@163.com
 * @return string
 */
    public function actionVideo(){
        $title = \Yii::$app->params['name'].'-资源稿库-视频库';
        $type = XedNewsType::VIDEO;
        return $this->render('special',[
            'title' => $title,
            'type' => $type
        ]);
    }

    /**
     * @Function 资源稿库-新闻库
     * @Author Weihuaadmin@163.com
     * @return string
     */
    public function actionNews(){
        $title = \Yii::$app->params['name'].'-资源稿库-新闻库';
        $type = XedNewsType::NEWS;
        return $this->render('special',[
            'title' => $title,
            'type' => $type
        ]);
    }
    /**
     * @Function 资源稿库-新闻库
     * @Author Weihuaadmin@163.com
     * @return string
     */
    public function actionPic(){
        $title = \Yii::$app->params['name'].'-资源稿库-图片库';
        $type = XedNewsType::PIC;
        return $this->render('special',[
            'title' => $title,
            'type' => $type
        ]);
    }

    /**
     * @Function 资源稿库 获取稿件
     * @Author Weihuaadmin@163.com
     */
    public function actionGetNewsList(){
        $type = $this->post('type');
        $wd = $this->post('wd');
        $start = $this->post('start');
        $end = $this->post('end');
        $query = XedNews::find()->filterWhere(['AND',
            ['newstype_id' => $type],
            ['like','title',$wd],
            ['is_usable'=>1],
        ]);
        if(!empty($start)){
            $startTime = strtotime($start. " 00:00:00");
            $query->andWhere(['>=','t_date',$startTime]);
        }
        if(!empty($end)){
            $endTime = strtotime($end. " 23:59:59");
            $query->andWhere(['<=','t_date',$endTime]);
        }
        $dealFunction = function($lists){
            foreach ($lists as $key => $list){
                $unitIds = XedNewsBelong::findAllByWhere(['news_id'=>$list['id']],'unit_id');
                $unitIds = ArrayHelper::getColumn($unitIds,'unit_id');
                $units = AdminUnit::findAllByWhere(['unitid' => $unitIds],['name'],['unitid'=>SORT_DESC]);
                $units = ArrayHelper::getColumn($units,'name');
                $units = implode('，',$units);
                $list['unit_name'] = $units;
                $list['type_name'] = XedNewsType::findValueByWhere(['id' => $list['fromtype']],['type']);
                $list['content_length'] = self::wordCount($list['content']);
                $list['t_date'] = ToolUtil::getDate($list['t_date'],'Y-m-d H:i:s');
                $lists[$key] = $list;
            }
            return $lists;
        };
        return $this->getJqTableData($query,$dealFunction);
    }


    /**
     * @Function 资源稿库种类设置
     * @Author Weihuaadmin@163.com
     */
    public function actionSourceType(){
        $menuName = self::getMenuName();
        $title = \Yii::$app->params['name'].'-'.$menuName;
        return $this->render('sourceType',[
            'title' => $title
        ]);
    }

    /**
     * @Function 获取资源稿库种类数据
     * @Author Weihuaadmin@163.com
     */
    public function actionGetType(){
        $query = XedNewsType::find();
        $query->filterWhere(['AND',
            ['>=','status','0']
        ]);
        $dealFunction = function($lists){
            foreach ($lists as $key => $list){
                $list['status_name'] = ToolUtil::getSelectType(XedNewsType::getStatusName(),$list['status']);
                $list['otype'] = $list['type'];
                $lists[$key] = $list;
            }
            return $lists;
        };
        return $this->getJqTableData($query,$dealFunction);
    }

    /**
     * @Function 添加稿件类型
     * @Author Weihuaadmin@163.com
     */
    public function actionAddType(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $id = $this->post('id');
            $checked = $this->post('checked');
            $name = $this->post('name');
            $postData = $this->post();
            if($id){
                $typeModel = XedNewsType::findOne($id);
                if($checked != ''){
                    $postData['type']['status'] = $checked;
                }
                if(!empty($name)){
                    $postData['type']['type'] = $name;
                }
            }else{
                $typeModel = new XedNewsType();
            }
            return $this->asJson($typeModel->addAndUpdate($postData));
        }
        return $this->render('_typeadd');
    }
}
