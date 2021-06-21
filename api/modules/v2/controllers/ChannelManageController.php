<?php

namespace api\modules\v2\controllers;

use services\api\ApiService;
use services\ErrorService;
use services\ResponseService;


/**
 * Site controller
 */
class ChannelManageController extends CommonController
{
    private $apiService;

    public function init()
    {
        parent::init();
        $this->apiService = new ApiService();
    }

    /**
     * 获取频道列表
     * @return array
     * @author rjl
     */
    public function actionGetChannelList()
    {
        userLog(3, 2, '获取频道列表');
        $data = $this->apiService->getChannelList();
        //当频道为空时，返回空数组
        if (empty($data)) {
            return ResponseService::response(ErrorService::CHANNEL_NO_EMPTY);
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
