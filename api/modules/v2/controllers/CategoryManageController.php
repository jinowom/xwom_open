<?php

namespace api\modules\v2\controllers;

use services\api\ApiService;
use services\ErrorService;
use services\ResponseService;

/**
 * Site controller
 */
class CategoryManageController extends CommonController
{

    private $apiService;

    public function init()
    {
        parent::init();
        $this->apiService = new ApiService();
    }

    /**
     * 根据频道id获取栏目类别
     * @param $channel_id int 频道id
     * @return array
     * @author rjl
     */
    public function actionGetCategoryList($channel_id)
    {
        userLog(3, 2, '根据频道id获取栏目类别');
        //频道id为空
        if (empty($channel_id)) {
            return ResponseService::response(ErrorService::CHANNEL_ID_NO_EMPTY);
        }
        $data = $this->apiService->getCategoryList($channel_id);

        //当栏目为空时，返回空数组
        if (empty($data)) {
            return ResponseService::response(ErrorService::CATEGORY_NO_EMPTY);
        }

        //给图片加上全局域名
        foreach ($data as $k => $v) {
            if ($v['surface_plot']) {
                $data[$k]['surface_plot'] = getVar('FILEURL') . $v['surface_plot'];
            }

            if ($v['pic']) {
                $data[$k]['pic'] = getVar('FILEURL') . $v['pic'];
            }
        }

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
    }
}
