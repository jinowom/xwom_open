<?php

namespace services\api;

use Yii;
use common\components\Service;

use backend\modules\xportal\models\XportalNews;
use backend\modules\xportal\models\XportalNewsImage;
use backend\modules\xportal\models\XportalChannel;
use backend\modules\xportal\models\XportalCategory;
use services\ResponseService;
use services\ErrorService;

/**
 * Class AtlasManageService
 * @package services\api
 * author:rjl
 */
class AtlasManageService extends Service
{
    /**
     * 获取文章的图集
     * @param $id int 文章id
     * @return array
     */
    static public function getNewsImage($id)
    {
        $getNewsImageList = XportalNewsImage::find()->from(XportalNewsImage::tableName())->select(['id', 'news_image_author', 'newsl_image_caption', 'news_image_name', 'news_image_url', 'created_id', 'created_at'])->where(['news_id' => $id, 'news_image_visible' => 1, 'is_del' => 0])->orderBy('position desc')->asArray()->all();

        $data = [];
        if ($getNewsImageList) {
            foreach ($getNewsImageList as $key => $value) {
                $data[$key]['id']                  = $value['id'];
                $data[$key]['news_image_author']   = $value['news_image_author'];
                $data[$key]['newsl_image_caption'] = $value['newsl_image_caption'];
                $data[$key]['news_image_name']     = $value['news_image_name'];
                $data[$key]['news_image_url']      = getVar('FILEURL') . $value['news_image_url'];
                $data[$key]['created_id']          = $value['created_id'];
                $data[$key]['created_at']          = $value['created_at'];
            }
        }

        return $data;
    }

}