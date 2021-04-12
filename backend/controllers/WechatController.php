<?php

namespace backend\controllers;

use backend\modules\xedit\models\XedNews;
use common\models\wechat\OuterMp;
use common\models\wechat\OuterMpNews;
use common\utils\ToolUtil;
use common\utils\WechatUtil;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * WeChat controller for the `xedit` module
 */
class WechatController extends BaseController
{
    protected $except = ['wechat/add','upload'];
    protected $is = 0;
    protected $limitRules = ['jpg','gif','png','temp','jpeg'];
    /**
     * 公众号列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list',[
            'title' => self::getMenuName()
        ]);
    }

    /**
     * 获取公众号数据
     * @return string
     */
    public function actionGetList()
    {
        $query = OuterMp::find();
        $dealFunction = function($lists){
            foreach ($lists as $key => $list){
                $lists[$key]['url'] = \Yii::$app->params['siteUrl'].urldecode(Url::toRoute(['site/entr','id'=>$list['id']]));
                $lists[$key]['type'] = ToolUtil::getSelectType(OuterMp::getType(),$list['type'],[]);
                $lists[$key]['status'] = ToolUtil::getSelectType([0 =>'禁用', 1 => '正常'],$list['status'],[]);
                $lists[$key]['valid_status'] = ToolUtil::getSelectType([0 =>'未接入', 1 => '已接入'],$list['valid_status'],[]);
            }
            return $lists;
        };
        return $this->getJqTableData($query,$dealFunction);
    }

    /**
     * @Function 添加公众号
     * @Author Weihuaadmin@163.com
     */
    public function actionAdd(){
        $id = $this->get('id');
        $model = new OuterMp();
        if(\Yii::$app->request->isPost){
            $pid = $this->post('id');
            if($pid){
                return $this->asJson($model->create($this->post(),$pid));
            }else{
                return $this->asJson($model->create($this->post()));
            }
        }
        return $this->render('add',[
            'title' => self::getMenuName(),
            'model' => !empty($id) ? $model->findValueByWhere(['id' => $id],[],[]) : $model
        ]);
    }

    /**
     * @Function 删除数据
     * @Author Weihuaadmin@163.com
     */
    public function actionDel(){
        $ids = $this->post('ids');
        $delRes = OuterMp::deleteAll("id=:id",[":id" => $ids]);
        if($delRes){
            return $this->asJson(ToolUtil::returnMsg(true,'删除成功'));
        }
        return $this->asJson(ToolUtil::returnMsg(false,'删除失败'));
    }

    /**
     * @Function 群发消息列表
     * @Author Weihuaadmin@163.com
     * @return string
     */
    public function actionSendList()
    {
        return $this->render('sendList',[
            'title' => self::getMenuName()
        ]);
    }

    /**
     * @Function 获取群发消息列表
     * @Author Weihuaadmin@163.com
     */
    public function actionGetSendList(){
        $query = OuterMpNews::find();
        $this->sidx = 'news_id';
        $dealFunction = function ($lists){
            foreach ($lists as $key => $list){
                $lists[$key]['create_time'] = ToolUtil::getDate($list['create_time'],"Y-m-d H:i:s");
            }
            return $lists;
        };
        return $this->getJqTableData($query,$dealFunction);
    }

    /**
     * @Function 添加群发
     * @Author Weihuaadmin@163.com
     * @return string
     */
    public function actionSendAdd()
    {
        if(\Yii::$app->request->isPost){

        }
        return $this->render('sendAdd',[
            'title' => self::getMenuName(),
            'model' => new OuterMpNews(),
            'status' => XedNews::getStatusName(),
            'type' => XedNews::getType(),
        ]);
    }
}
