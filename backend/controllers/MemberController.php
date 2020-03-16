<?php
/**
 * Created by PhpStorm.
 * User: spacingliu@163.com
 * Date: 2019/10/16 001614:54
 * Describe:会员操作列表
 */

namespace backend\controllers;

class MemberController extends BaseController
{
    protected $except = ['index','user'];

    /**
     * @Function member 列表
     * @Author Weihuaadmin@163.com
     */
    public function actionList()
    {
        return $this->render('list');
    }
    public function actionUser(){
        $list =[];
        for($i=0;$i<15;$i++){
            $list[] = array('id'=>'1000'.$i,'username'=>'雨落凡尘','email'=>'xianxin@layui.com','sex'=>'男','city'=>'浙江杭州',
                'sign'=>'点击此处，显示更多。当内容超出时，点击单元格会自动显示更多内容。','experience'=>'116','ip'=>'192.168.0.8',
                'logins'=>'108','joinTime'=>'2019-10-16','dw_xinzhi'=>array('id'=>90,'titel'=>'小学'));
        }
        $res = ['code'=>0,'msg'=>'success','count'=>500,'data'=>$list];
        return json_encode($res);
    }
}