<?php

namespace api\modules\v1\controllers;

use services\ErrorService;
use services\ResponseService;
use backend\modules\xportal\models\xunsearch\{News};
use backend\modules\xportal\models\XportalChannel;
use backend\modules\xportal\models\XportalCategoryBind;
use backend\modules\xportal\models\XportalNewsImage;

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
        $content     = $this->get('content', null); //搜索值
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

        //附属标识
        $parameter = $this->get('parameter', 'project');
        $getChannelOne = XportalChannel::find()->from('xportal_channel')->select(['channel_id'])->Where(['is_del' => 0, 'parameter' => $parameter])->asArray()->one();

        //给图片加上全局域名
        foreach ($data as $k => $v) {
            //如果和专题匹配上打上标识
            $categoryBindData = XportalCategoryBind::find()->from('xportal_category_bind')->select('category_id')->where(['is_del' => 0, 'parentid' => $getChannelOne['channel_id']])->column();

            //判断是否是专题
            $data[$k]['project_mark'] = in_array($v['catid'], $categoryBindData) ? 1 : 0;
            //1大图 0小图
            // $data[$k]['shuffling_index'] = $v['shuffling_index'] == 0 ? 0 : 1;

            //统计视频有几节课
            $data[$k]['news_movie_uir_count'] = 0;
            $data[$k]['news_video_length'] = 0;
            if (!empty($v['news_movie_uir'])) {
                $movie_uir = json_decode($v['news_movie_uir'], true);
                $data[$k]['news_movie_uir'] = $movie_uir;
                $data[$k]['news_movie_uir_count'] = count($movie_uir);
                $data[$k]['news_video_length'] = $movie_uir[0]['video_length'];
            }

            //查询图集个数
            $data[$k]['news_image_count'] = 0;
            if ($v['news_type_id'] == 2) {
                $getNewsImageCount = XportalNewsImage::find()->from(XportalNewsImage::tableName())->where(['news_id' => $v['id'], 'news_image_visible' => 1, 'is_del' => 0])->count();
                $data[$k]['news_image_count'] = $getNewsImageCount;
            }

            if ($v['thumbnail']) {
                $data[$k]['thumbnail'] = getVar('FILEURL') . $v['thumbnail'];
            }

            if ($v['shuffling']) {
                $data[$k]['shuffling'] = getVar('FILEURL') . $v['shuffling'];
            }
        }

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
    }
}
