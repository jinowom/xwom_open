<?php
namespace api\modules\v1\controllers;

use backend\modules\xportal\models\XportalChannel;
use backend\modules\xportal\models\XportalCategory;
use backend\modules\xportal\models\XportalCategoryBind;
use backend\modules\xportal\models\XportalNews;
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
        $parameter = $this->get('parameter', null); //附属标识

        if (empty($parameter)) {
            return ResponseService::response(ErrorService::AFFILIATE_ID_EMPTY);
        }

        $getChannelOne = XportalChannel::find()->from('xportal_channel')->select(['channel_id','is_link','bank_url'])->Where(['is_del' => 0, 'parameter' => $parameter])->asArray()->one();

        if (empty($getChannelOne)) {
            return ResponseService::response(ErrorService::TYPE_EMPTY);
        }

        //如果是报纸，返回链接
        if($getChannelOne['is_link'] == 1){
            return ResponseService::response(ErrorService::STATUS_SUCCESS,$getChannelOne);
        }

        //判断频道是否存在
        if (!XportalChannel::findOne($getChannelOne['channel_id'])) {
            return ResponseService::apiResponse(ErrorService::CHANNEL_NO_EMPTY, '此数据不存在', (object)[]);
        }

        $getChannelList = XportalChannel::find()->from('xportal_channel')->select(['channel_id', 'channel_ch_name'])->Where(['is_del' => 0, 'parent_id' => $getChannelOne['channel_id']])->orderBy("channel_listorder DESC")->asArray()->all();
        if (empty($getChannelList)) {
            return ResponseService::response(ErrorService::CHANNEL_ID_NO_EMPTY);
        }

        $categoryBindData = XportalCategoryBind::find()->from('xportal_category_bind')->select('category_id')->where(['is_del' => 0, 'parentid' => $getChannelList[0]['channel_id']])->column();

        if (empty($categoryBindData)) {
            return ResponseService::response(ErrorService::CATEGORY_BIND_EMPTY);
        }

        $getCategoryList = XportalCategory::find()->from('xportal_category')->select(['catid', 'catname', 'bank_url', 'surface_plot', 'pic', 'parameter'])->andWhere(['in', 'catid', $categoryBindData])->andWhere(['ismenu' => 1])->asArray()->all();

        if (empty($getCategoryList)) {
            return ResponseService::response(ErrorService::CHANNEL_NO_EMPTY);
        }
        //给图片添加域名
        foreach ($getCategoryList as $k => $v) {
            if(!empty($v['surface_plot'])){
                $getCategoryList[$k]['surface_plot']      = getVar('FILEURL').$v['surface_plot'];
            }
            if(!empty($v['pic'])){
                $getCategoryList[$k]['pic']      = getVar('FILEURL').$v['pic'];    
            }
        }

        //根据频道id调取文章
        /* $portalnews = getVar('PORTALNEWS');//已发布标识
        $getNewsList = XportalNews::find()->from(XportalNews::tableName())->select(['id', 'title', 'created_at', 'copyfrom', 'shuffling', 'thumbnail', 'news_type_id','news_movie_uir'])->where(['is_del' => 0, 'status' => $portalnews, 'channelid' => $getChannelOne['channel_id']])->asArray()->all();;

        if(!empty($getNewsList)){
            foreach ($getNewsList as $k => $v) {
                //统计视频时长
                $getNewsList[$k]['news_video_length'] = 0;
                $getNewsList[$k]['news_movie_uir'] = '';
                if (isset($v['news_movie_uir'])) {
                    $movie_uir = json_decode($v['news_movie_uir'], true);
                    $getNewsList[$k]['news_movie_uir'] = $movie_uir;
                    $getNewsList[$k]['news_video_length'] = $movie_uir[0]['video_length'];
                }
            }
        } */
        
        //组装数据
        $data = [];
        $data['channel'] = !empty($getChannelList) ? $getChannelList : [];
        $data['category'] = !empty($getCategoryList) ? $getCategoryList : [];
        // $data['news'] = !empty($getNewsList) ? $getNewsList : [];

        return ResponseService::response(ErrorService::STATUS_SUCCESS,$data);
    }


    /**
     * APP底部电视和电台
     */
    public function actionGetTvRadio()
    {
        userLog(3, 2, '获取电视和电台列表');
        $parameter = $this->get('parameter', null); //附属标识

        $page     = $this->get('page', 1);//页数
        $pageSize = $this->getPage();
        $offset   = ($page - 1) * $pageSize;
        $sortord  = $this->get('sortord', 'top_doing desc, listorder desc, updated_at desc, created_at'); //排序

        if (empty($parameter)) {
            return ResponseService::response(ErrorService::AFFILIATE_ID_EMPTY);
        }

        $getChannelOne = XportalChannel::find()->from('xportal_channel')->select(['channel_id'])->Where(['is_del' => 0, 'parameter' => $parameter])->asArray()->one();

        if (empty($getChannelOne)) {
            return ResponseService::response(ErrorService::TYPE_EMPTY);
        }

        $categoryBindData = XportalCategoryBind::find()->from('xportal_category_bind')->select('category_id')->where(['is_del' => 0, 'parentid' => $getChannelOne])->column();
        
        if (empty($categoryBindData)) {
            return ResponseService::response(ErrorService::CATEGORY_BIND_EMPTY);
        }

        
        $getCategoryList = XportalCategory::find()->from('xportal_category')->select(['catname'])->andWhere(['in', 'catid', $categoryBindData])->andWhere(['ismenu' => 1])->orderBy("listorder DESC")->asArray()->one();

        //如果没找到栏目，就给一个默认的栏目title
        $getCategoryCatname = [];
        if (empty($getCategoryList)) {
            $getCategoryCatname['catname'] = '';
            if ($parameter == 'television') {
                $getCategoryCatname['catname'] = '冀中能源TV';
            }
            if ($parameter == 'broadcasting') {
                $getCategoryCatname['catname'] = '股份之声FM';
            }
        }
        
        //根据频道id调取文章
        $portalnews = getVar('PORTALNEWS');//已发布标识
        $getNewsList = XportalNews::find()->from(XportalNews::tableName())->select(['id', 'title', 'created_at', 'copyfrom', 'shuffling', 'thumbnail', 'news_type_id','news_movie_uir'])->where(['is_del' => 0, 'status' => $portalnews, 'channelid' => $getChannelOne['channel_id']]);

        $this->sidx = $sortord;
        $data = $this->getPageSize($getNewsList,$offset);

        if(!empty($data)){
            foreach ($data as $k => $v) {
                //统计视频时长
                $data[$k]['news_video_length'] = 0;
                $data[$k]['news_movie_uir']    = null;
                if (!empty($v['thumbnail'])) {
                    $data[$k]['thumbnail'] = getVar('FILEURL') . $v['thumbnail'];
                }
                if (!empty($v['shuffling'])) {
                    $data[$k]['shuffling'] = getVar('FILEURL') . $v['shuffling'];
                }

                if (isset($v['news_movie_uir'])) {
                    $movie_uir                  = json_decode($v['news_movie_uir'], true);
                    $data[$k]['news_movie_uir'] = $movie_uir;
                    foreach ($movie_uir as $key => $value) {
                        if ($value['status'] == 1) {
                            $data[$k]['news_movie_uir'][$key]['file']                    = getVar('FILEURL') . $value['file'];
                        }
                    }
                    $data[$k]['news_video_length'] = $movie_uir[0]['video_length'];
                }
            }
        }

        //组装数据
        $list = [];
        $list['category'] = !empty($getCategoryList) ? $getCategoryList : $getCategoryCatname;
        $list['data'] = !empty($data) ? $data : [];


        return ResponseService::response(ErrorService::STATUS_SUCCESS,$list);
    }
    
}
