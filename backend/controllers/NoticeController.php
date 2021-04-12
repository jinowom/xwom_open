<?php
/**
 * Created by PhpStorm.
 * User: spacing
 * Date: 2019/10/18
 * Time: 13:05
 */

namespace backend\controllers;

use common\models\AdminNotice;
use common\models\User;
use common\utils\ToolUtil;
use yii\helpers\ArrayHelper;

class NoticeController extends BaseController
{
    protected $except = ['notice\index','index'];
    /**
     * @Function 获取部门数据
     * @Author 1042463605@qq.com
     */
    public function actionIndex(){
        $isSuper = self::IsSuperAdmin($this->user_id);
        $noticeName = $this->get('noticeName');
        return $this->render('noticelist',['isSuper'=>$isSuper]);
    }
    /**
     * @Function 单位管理
     * @Author 1042463605@qq.com
     * @return string
     */
    public function actionNoticeList(){
        $isSuper = self::IsSuperAdmin($this->user_id);
        $noticeName = $this->get('noticeName');
        return $this->render('noticelist',['noticeName'=>$noticeName,'isSuper'=>$isSuper]);
    }

    /**
     * @Function 获取单位管理数据
     * @Author 1042463605@qq.com
     */
    public function actionGetNoticeList(){
        $noticeName = $this->post('noticeName');
        $isSuper = self::IsSuperAdmin($this->user_id);
        if($isSuper){
            if($noticeName !=''){
                $query = AdminNotice::find()->filterWhere(['AND',['like','title',$noticeName]]);
            }else{
                $query = AdminNotice::find();
            }
        }else{
            echo '根据所在的读取';exit;
        }

        $this->sidx = 'created_at';
        $dealFunction = function ($lists){
            foreach ($lists as $key => $list){
                $lists[$key]['created_at'] = ToolUtil::getDate($list['created_at'],"Y-m-d H:i:s");
                $lists[$key]['updated_at'] = ToolUtil::getDate($list['updated_at'],"Y-m-d H:i:s");
            }
            return $lists;
        };
        return $this->getJqTableData($query,$dealFunction);
    }

    /**
     * @Function 删除单位
     * @Author 1042463605@qq.com
     */
    public function actionDelUint(){
        $ids = $this->post('ids');
        return $this->asJson(AdminNotice::delAdmin($ids));
    }
    /**
     * @Function 修改通知信息
     * @Author 1042463605@qq.com
     * @return \yii\web\Response
     */
    public function actionNoticeEdit(){
        if(\Yii::$app->request->isGet){
            return $this->render('_noticeadd',['noticeInfo' =>[]]);
            exit;
        }
        $postData = $this->post();
        if($postData){
            if(isset($postData['notice']['title']) && $postData['notice']['noticeid'] ==''){
                $notice = new  AdminNotice();
                $notice->title = $postData['notice']['title'];
                $notice->content = $postData['notice']['content'];
                $notice->created_at = time();
                $notice->created_user = $this->user_id;
                $notice->updated_at = time();
                if($notice->save()){
                    $return = ToolUtil::returnAjaxMsg(true,'新增成功');
                }else{
                    $return = ToolUtil::returnAjaxMsg(true,'操纵失败');
                }
            }else{
                $Res = AdminNotice::updateAll(['title' => $postData['notice']['title'],'updated_user' => $this->user_id,'content'=> $postData['notice']['content'],'updated_at'=>time()]," noticeid = :noticeid",[":noticeid" => $postData['notice']['noticeid']]);
                if($Res){
                    $return = ToolUtil::returnAjaxMsg(true,'编辑成功');
                }else{
                    $return = ToolUtil::returnAjaxMsg(false,'操作失败');
                }
            }
        }else{
            $return = ToolUtil::returnAjaxMsg(false,'操作失败');
        }
        return $this->asJson($return);
    }
    /**
     * @Function 删除通知
     * @Author 1042463605@qq.com
     */
    public function actionDelNotice(){
        $ids = $this->post('ids');
        return $this->asJson(AdminNotice::delNotice($ids));
    }

    /**
     * @Function 添加通知页面
     * @Author 1042463605@qq.com
     * @return string
     */
    public function actionAddNotice(){
        $ids = $this->get('ids');
        if($ids){
            $noticeInfo = AdminNotice::findValueByWhere(['noticeid' => $ids],[],['noticeid' => SORT_DESC]);
            return $this->render('_noticeadd',['noticeInfo' => $noticeInfo]);
        }else{
            return $this->render('_noticeadd');
        }
    }
}