<?php
/**
 * Created by PhpStorm.
 * User: wuhaibo
 * Date: 2020/12/19
 * Time: 13:04
 * 统计统计相关控制器
 */

namespace backend\controllers;

use Yii;
use backend\modules\xportal\models\{ XportalNewsStatusStatic,XportalNewsTypeStatic,XportalNewsStatic};
use common\models\Admin;
use backend\controllers\BaseController;

class StaticController extends BaseController{
    //获取栈内短消息
    public function actionGetShortMessage(){
        //获取工作流节点
        $work_status = XportalNewsStatusStatic::find()->select('status_label as label, news_count as newsCount')
                                     ->orderBy("status_order desc")
                                     ->asArray()->all();
        return $this->returnSuccess($work_status,'success');
    }

    //获取一周内发布的新闻数量统计情况
    public function actionGetNewsReleaseCount(){
        $selTime = Yii::$app->request->get('selTime',"");
        if(!empty($selTime)){
            $time = explode('-',$selTime);
            $startTime = trim($time[0]).trim($time[1]).trim($time[2]);
            $endTime = trim($time[3]).trim($time[4]).trim($time[5]);
        }else{
            $startTime = date('Ymd',time()-7*24*60*60);
            $endTime = date('Ymd',time());
        }
        $data = XportalNewsStatic::find()->select('news_count,created_at')
                                         ->andWhere(['and',['>=', "FROM_UNIXTIME(created_at,'%Y%m%d')", $startTime],['<=', "FROM_UNIXTIME(created_at,'%Y%m%d')",$endTime]])
                                         ->orderBy("created_at ")
                                         ->asArray()->all();
        foreach ($data as $key => $value) {
            $data['timeData'][$key] = date('Y-m-d',$value['created_at']);
            $data['newsDate'][$key] = $value['news_count'];
        }
        return $this->returnSuccess($data,'success');
    }
    //获取各个资源库的文章数量
    public function actionGetNewsCount(){
        $data = XportalNewsTypeStatic::getNewsTypeStatic();
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $data[$key]['name'] = $value['name'].'('.$value['value'].')';
                $newsType[$key]['value']  = $value['value'];
                $list['key'][] = $data[$key]['name'];
            }
            $list['newsType'] = $data;
            // print_r($list);
           return $this->returnSuccess($list,'success');
        }else{
           return $this->returnError('','未找到数据');
        }
    }
    //获取用户登录次数
    public function actionGetUserCount(){
        $data = Admin::find()->select(['login_count','username'])->asArray()->all();
        $info['login_count'] = [];
        $info['username'] = [];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $info['login_count'][$key] =$value['login_count'];
                $info['username'][$key] = $value['username'];
            }
        }
        return $this->returnSuccess($info,'success');

    }
}