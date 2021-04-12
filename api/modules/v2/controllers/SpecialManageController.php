<?php

namespace api\modules\v2\controllers;

use backend\modules\xportal\models\XportalChannel;
use backend\modules\xportal\models\XportalNews;
use services\api\SpecialManageService;
use services\ErrorService;
use services\ResponseService;


/**
 * Site controller
 */
class SpecialManageController extends CommonController
{

    /**
     * APP底部附属方法
     */
    public function actionGetAffiliated()
    {
        userLog(3, 2, 'APP底部视频');
        $parameter = $this->get('parameter', null); //附属标识

        if (empty($parameter)) {
            return ResponseService::response(ErrorService::AFFILIATE_ID_EMPTY);
        }

        $getChannelOne = XportalChannel::find()->from('xportal_channel')->select(['channel_id', 'is_link', 'bank_url'])->Where(['is_del' => 0, 'parameter' => $parameter])->asArray()->one();

        if (empty($getChannelOne)) {
            return ResponseService::response(ErrorService::TYPE_EMPTY);
        }

        //如果是报纸，返回链接
        if ($getChannelOne['is_link'] == 1) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS, $getChannelOne);
        }

        //判断频道是否存在
        if (!XportalChannel::findOne($getChannelOne['channel_id'])) {
            return ResponseService::apiResponse(ErrorService::CHANNEL_NO_EMPTY, '此数据不存在', (object)[]);
        }

        $getChannelList = XportalChannel::find()->from('xportal_channel')->select(['channel_id', 'channel_ch_name'])->Where(['is_del' => 0, 'parent_id' => $getChannelOne['channel_id']])->orderBy("channel_listorder DESC")->asArray()->all();
        if (empty($getChannelList)) {
            return ResponseService::response(ErrorService::CHANNEL_ID_NO_EMPTY);
        }

        //获取栏目信息
        $getCategoryList = SpecialManageService::getCategoryList($getChannelList);
        if ($getCategoryList['code'] != 200) {
            return ResponseService::apiResponse($getCategoryList['code'], $getCategoryList['message']);
        }

        //组装数据
        $data             = [];
        $data['channel']  = !empty($getChannelList) ? $getChannelList : [];
        $data['category'] = !empty($getCategoryList['data']) ? $getCategoryList['data'] : [];

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
    }


    /**
     * APP底部电视和电台
     */
    public function actionGetTvRadio()
    {
        userLog(3, 2, '获取电视和电台列表');
        $parameter  = $this->get('parameter', null); //附属标识
        $this->sidx = 'top_doing desc, listorder desc, updated_at desc, created_at';//排序

        if (empty($parameter)) {
            return ResponseService::response(ErrorService::AFFILIATE_ID_EMPTY);
        }

        $getChannelOne = XportalChannel::find()->from('xportal_channel')->select(['channel_id'])->Where(['is_del' => 0, 'parameter' => $parameter])->asArray()->one();

        if (empty($getChannelOne)) {
            return ResponseService::response(ErrorService::TYPE_EMPTY);
        }

        //获取栏目信息
        $getCategoryList = SpecialManageService::getTvRadioCategory($getChannelOne);
        if ($getCategoryList['code'] != 200) {
            return ResponseService::apiResponse($getCategoryList['code'], $getCategoryList['message']);
        }

        //如果没找到栏目，就给一个默认的栏目title
        $getCategoryCatname = [];
        if (empty($getCategoryList['data'])) {
            $getCategoryCatname['catname'] = '';
            if ($parameter == 'television') {
                $getCategoryCatname['catname'] = '冀中能源TV';
            }
            if ($parameter == 'broadcasting') {
                $getCategoryCatname['catname'] = '股份之声FM';
            }
        }

        //根据频道id调取文章
        $portalnews  = getVar('PORTALNEWS');//已发布标识
        $getNewsList = XportalNews::find()->from(XportalNews::tableName())->select(['id', 'title', 'created_at', 'copyfrom', 'shuffling', 'thumbnail', 'news_type_id', 'news_movie_uir'])->where(['is_del' => 0, 'status' => $portalnews, 'channelid' => $getChannelOne['channel_id']]);

        $data = $this->getJqTableData($getNewsList, "");

        //获取电视和电台数据
        $data = SpecialManageService::getTvRadioList($data);
        //组装数据
        $list             = [];
        $list['category'] = !empty($getCategoryList['data']) ? $getCategoryList['data'] : $getCategoryCatname;
        $list['data']     = !empty($data) ? $data : [];

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $list);
    }

}
