<?php
/**
 * Created by PhpStorm.
 * User: LIUYANCHENG
 * Date: 2019/10/12
 * Time: 14:05
 */

namespace backend\controllers;


class WeixinController extends BaseController
{
    protected $except = ['index','login'];
    protected $wb_ClientKey = '';
    protected $wb_ClientSecret = '';
    /**
     * @Function
     * @Author 1042463605@qq.com
     * @return string
     */
    public function actionIndex(){
        return $this->render('login');
    }


    public function actionLogin(){
        return $this->render('login');
    }






}