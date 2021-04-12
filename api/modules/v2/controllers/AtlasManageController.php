<?php

namespace api\modules\v2\controllers;

use Yii;
use backend\modules\xportal\models\XportalNews;
use backend\modules\xportal\models\XportalChannel;
use backend\modules\xportal\models\XportalCategory;
use services\ErrorService;
use services\ResponseService;
use services\api\AtlasManageService;

/**
 * Site controller
 */
class AtlasManageController extends CommonController
{
    public function init()
    {
        parent::init();
    }


    /**
     * 获取图集列表
     * @return array
     */
    public function actionGetAtlasList()
    {
        userLog(3, 2, '获取图集列表');
        $type     = Yii::$app->request->get('type', null);//类型
        $id       = Yii::$app->request->get('id', null);//频道id和栏目id
        $newsType = Yii::$app->request->get('news_type', 2);//资源库类型

        //类型为空
        if (empty($type)) {
            return ResponseService::response(ErrorService::TYPE_EMPTY);
        }
        //查询类型是否存在
        if (!in_array($type, [1, 2])) {
            return ResponseService::response(ErrorService::TYPE_ERROR);
        }

        //频道id和栏目id为空
        if (empty($id)) {
            return ResponseService::response(ErrorService::CHANNEL_OR_CATEGORY_ID_EMPTY);
        }

        //根据类型组装查询条件
        $where = [];
        if ($type == 1 && !empty($id)) {
            //判断频道是否存在
            if (!XportalChannel::findOne($id)) {
                return ResponseService::apiResponse(ErrorService::CHANNEL_NO_EMPTY, '此数据不存在', (object)[]);
            }
            $where = ['channelid' => $id, 'is_del' => 0, 'status' => 'release'];
        } elseif ($type == 2 && !empty($id)) {
            //判断栏目是否存在
            if (!XportalCategory::findOne($id)) {
                return ResponseService::apiResponse(ErrorService::CATEGORY_NO_EMPTY, '此数据不存在', (object)[]);
            }
            $where = ['catid' => $id, 'is_del' => 0, 'status' => 'release'];
        }

        //查询条件不能为空
        if (empty($where)) {
            return ResponseService::response(ErrorService::PARAMETER_ERROR);
        }

        if (empty($newsType)) {
            return ResponseService::response(ErrorService::NOT_FOUND_REPOSITORY);
        }

        $query      = XportalNews::find()->from(XportalNews::tableName())->select(['id', 'title'])->where($where)->andWhere(['news_type_id' => $newsType]);
        $this->sidx = 'top_doing desc, listorder desc, updated_at desc, created_at';//排序
        $data       = $this->getJqTableData($query, "");

        if (empty($data)) {
            return ResponseService::apiResponse(ErrorService::STATUS_SUCCESS, '暂无数据', (object)[]);
        }
        //查询每个文章的图集
        foreach ($data as $k => $v) {
            $data[$k]['image'] = AtlasManageService::getNewsImage($v['id']);
        }
        return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
    }
}
