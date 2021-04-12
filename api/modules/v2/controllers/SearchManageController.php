<?php

namespace api\modules\v2\controllers;

use services\api\SearchManageService;
use services\ErrorService;
use services\ResponseService;
use backend\modules\xportal\models\xunsearch\{News};

/**
 * Site controller
 */
class SearchManageController extends CommonController
{

    public function init()
    {
        parent::init();
    }

    /**
     * 搜索
     * @param $content string 搜索值
     * @return array
     * @author rjl
     */
    public function actionSearch()
    {
        userLog(3, 2, '搜索');
        //搜索值
        $content = $this->get('content', null); //搜索值
        if (empty($content)) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        }
        $pageSize = $this->getPage();
        $page     = $this->get('page', 1); //页数
        $offset   = ($page - 1) * $pageSize;

        $data = News::findNewsAll($content)->offset($offset)->limit($this->pageSize)->asArray()->all();

        //当栏目为空时，返回空数组
        if (empty($data)) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        }

        $data = SearchManageService::getSearchList($data);

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
    }
}
