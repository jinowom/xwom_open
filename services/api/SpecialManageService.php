<?php

namespace services\api;

use common\components\Service;
use backend\modules\xportal\models\XportalCategory;
use backend\modules\xportal\models\XportalCategoryBind;
use services\ErrorService;
use services\ResponseService;

/**
 * Class ApiService
 * @package services\api
 * author:rjl
 */
class SpecialManageService extends Service
{
    /**
     * 获取栏目信息
     * @param $getChannelList
     * @return array
     */
    static public function getCategoryList($getChannelList)
    {
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
            if (!empty($v['surface_plot'])) {
                $getCategoryList[$k]['surface_plot'] = getVar('FILEURL') . $v['surface_plot'];
            }
            if (!empty($v['pic'])) {
                $getCategoryList[$k]['pic'] = getVar('FILEURL') . $v['pic'];
            }
        }
        return ResponseService::response(ErrorService::STATUS_SUCCESS, $getCategoryList);
    }


    /**
     * 获取栏目信息
     * @param
     * @return array
     */
    static public function getTvRadioCategory($getChannelOne)
    {
        $categoryBindData = XportalCategoryBind::find()->from('xportal_category_bind')->select('category_id')->where(['is_del' => 0, 'parentid' => $getChannelOne])->column();

        if (empty($categoryBindData)) {
            return ResponseService::response(ErrorService::CATEGORY_BIND_EMPTY);
        }

        $getCategoryList = XportalCategory::find()->from('xportal_category')->select(['catname'])->andWhere(['in', 'catid', $categoryBindData])->andWhere(['ismenu' => 1])->orderBy("listorder DESC")->asArray()->one();

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $getCategoryList);
    }

    /**
     * 获取电视和电台数据
     * @param $data
     * @return mixed
     */
    static public function getTvRadioList($data)
    {
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
                        $data[$k]['news_movie_uir'][$key]['file'] = getVar('FILEURL') . $value['file'];
                    }
                }
                $data[$k]['news_video_length'] = $movie_uir[0]['video_length'];
            }
        }
        return $data;
    }


}