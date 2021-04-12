<?php

namespace services\api;

use Yii;
use common\components\Service;

use backend\modules\xportal\models\XportalChannel;
use backend\modules\xportal\models\XportalCategoryBind;
use backend\modules\xportal\models\XportalNewsImage;

/**
 * Class ApiService
 * @package services\api
 * author:rjl
 */
class SearchManageService extends Service
{
    /**
     * 搜索列表
     * @param $data
     * @return mixed
     */
    static public function getSearchList($data)
    {
        $parameter     = Yii::$app->request->get('parameter', 'project'); //附属标识
        $getChannelOne = XportalChannel::find()->from('xportal_channel')->select(['channel_id'])->Where(['is_del' => 0, 'parameter' => $parameter])->asArray()->one();

        //给图片加上全局域名
        foreach ($data as $k => $v) {
            //如果和专题匹配上打上标识
            $categoryBindData = XportalCategoryBind::find()->from('xportal_category_bind')->select('category_id')->where(['is_del' => 0, 'parentid' => $getChannelOne['channel_id']])->column();

            //判断是否是专题
            $data[$k]['project_mark'] = in_array($v['catid'], $categoryBindData) ? 1 : 0;

            //统计视频有几节课
            $data[$k]['news_movie_uir_count'] = 0;
            $data[$k]['news_video_length']    = 0;
            if (!empty($v['news_movie_uir'])) {
                $movie_uir                        = json_decode($v['news_movie_uir'], true);
                $data[$k]['news_movie_uir']       = $movie_uir;
                $data[$k]['news_movie_uir_count'] = count($movie_uir);
                $data[$k]['news_video_length']    = $movie_uir[0]['video_length'];
            }

            //查询图集个数
            $data[$k]['news_image_count'] = 0;
            if ($v['news_type_id'] == 2) {
                $getNewsImageCount            = XportalNewsImage::find()->from(XportalNewsImage::tableName())->where(['news_id' => $v['id'], 'news_image_visible' => 1, 'is_del' => 0])->count();
                $data[$k]['news_image_count'] = $getNewsImageCount;
            }

            if ($v['thumbnail']) {
                $data[$k]['thumbnail'] = getVar('FILEURL') . $v['thumbnail'];
            }

            if ($v['shuffling']) {
                $data[$k]['shuffling'] = getVar('FILEURL') . $v['shuffling'];
            }
        }

        return $data;
    }

}