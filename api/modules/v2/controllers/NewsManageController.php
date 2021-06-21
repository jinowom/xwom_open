<?php

namespace api\modules\v2\controllers;

use services\api\NewsManageService;
use Yii;
use backend\modules\xportal\models\XportalNewsSeniority;
use backend\modules\xportal\models\XportalNews;
use backend\modules\xportal\models\XportalChannel;
use backend\modules\xportal\models\XportalCategory;
use backend\modules\xportal\models\XportalCategoryBind;
use backend\modules\xportal\models\XportalNewsImage;
use backend\modules\xportal\models\XportalSpecial;
use backend\modules\xportal\models\XportalSinglePage;
use services\ErrorService;
use services\ResponseService;

/**
 * Site controller
 */
class NewsManageController extends CommonController
{
    private $portalnews; //已发布标识
    private $banner_limit;
    private $recommendarticle_limit; //用于app端推荐文章的显示数量

    public function init()
    {
        parent::init();
        $this->portalnews             = getVar('PORTALNEWS'); //已发布标识
        $this->banner_limit           = getVar('BANNERLIMIT'); //banner中limit数量
        $this->recommendarticle_limit = getVar('RECOMMENDARTICLE'); //用于app端推荐文章的显示数量
    }

    /**
     * 获取轮播图
     * @return array
     * @author rjl
     */
    public function actionBannerList()
    {
        userLog(3, 2, '获取轮播图');

        $type = Yii::$app->request->get('type', null); //类型
        $id   = Yii::$app->request->get('id', null); //频道id和栏目id
        //类型为空
        if (empty($type)) {
            return ResponseService::response(ErrorService::TYPE_EMPTY);
        }
        //查询类型是否存在
        if (!in_array($type, [1, 2, 3])) {
            return ResponseService::response(ErrorService::TYPE_ERROR);
        }
        //调用轮播图services
        $data = NewsManageService::getBannerList($type, $id);

        if (empty($data)) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        }
        //给图片加上全局域名
        foreach ($data as $k => $v) {
            if ($v['shuffling']) {
                $data[$k]['shuffling'] = getVar('FILEURL') . $v['shuffling'];
            }
        }
        return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
    }

    /**
     * 获取文章列表
     * @return array|\yii\web\Response
     * @author rjl
     */
    public function actionArticleList()
    {
        userLog(3, 2, '获取文章列表');

        $type       = $this->get('type', null); //类型
        $id         = $this->get('id', null); //频道id和栏目id
        $newsType   = $this->get('news_type', null); //资源库类型
        $page       = $this->get('page', 1); //页数
        $this->sidx = 'top_doing desc, listorder desc, updated_at desc, created_at';//排序

        //类型为空
        if (empty($type)) {
            return ResponseService::response(ErrorService::TYPE_EMPTY);
        }
        //查询类型是否存在
        if (!in_array($type, [1, 2])) {
            return ResponseService::response(ErrorService::TYPE_ERROR);
        }

        //根据类型组装查询条件
        $where = [];
        if ($type == 1 && !empty($id)) {
            //判断频道是否存在
            if (!XportalChannel::findOne($id)) {
                return ResponseService::apiResponse(ErrorService::CHANNEL_NO_EMPTY, '此数据不存在', (object)[]);
            }
            $where = ['is_del' => 0, 'status' => getVar('PORTALNEWS'), 'channelid' => $id];
        } elseif ($type == 2 && !empty($id)) {
            //判断栏目是否存在
            if (!XportalCategory::findOne($id)) {
                return ResponseService::apiResponse(ErrorService::CATEGORY_NO_EMPTY, '此数据不存在', (object)[]);
            }
            $where = ['is_del' => 0, 'status' => getVar('PORTALNEWS'), 'catid' => $id];
        }

        //查询条件不能为空
        if (empty($where)) {
            return ResponseService::response(ErrorService::PARAMETER_ERROR);
        }

        //如果是直播走特定方法
        if (!empty($newsType) && $newsType == 5) {
            $getLiveList = $this->actionGetLiveList($type, $id, $newsType, $this->sidx);
            return $getLiveList;
        }

        //专题数据
        $xportalSpecial = NewsManageService::getSpecial($id, $page);
        //栏目信息
        $category = NewsManageService::getCategory($type, $id);
        //获取文章列表
        $query = NewsManageService::getArticleList($where, $newsType);
        $data  = $this->getJqTableData($query, "");

        if (empty($data)) {
            return ResponseService::apiResponse(ErrorService::STATUS_SUCCESS, '暂无数据', (object)[]);
        }
        //获取文章列表组装文章数据
        $data = NewsManageService::getNewsList($data);

        //组装数据
        $list             = [];
        $list['special']  = !empty($xportalSpecial) ? $xportalSpecial : (object)[];
        $list['category'] = !empty($category) ? $category : (object)[];
        $list['data']     = !empty($data) ? $data : [];

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $list);
    }

    /**
     * 直播列表
     * @param $type
     * @param string $id
     * @param $newsType
     * @param $sortord
     * @return array
     */
    public function actionGetLiveList($type, $id = '', $newsType, $sortord)
    {
        $this->sidx = $sortord;//排序
        //类型为空
        if (empty($type)) {
            return ResponseService::response(ErrorService::TYPE_EMPTY);
        }
        //查询类型是否存在
        if (!in_array($type, [1, 2])) {
            return ResponseService::response(ErrorService::TYPE_ERROR);
        }

        //获取直播数据
        $query = NewsManageService::getLiveList($type, $id, $newsType);
        $data  = $this->getJqTableData($query, "");

        if (empty($data)) {
            $object             = [];
            $object['category'] = (object)[];
            $object['data']     = [];

            return ResponseService::apiResponse(ErrorService::STATUS_SUCCESS, '暂无数据', (object)[]);
        }
        //获取文章列表组装文章数据
        $data = NewsManageService::getNewsList($data);
        //组装数据
        $list             = [];
        $list['category'] = !empty($categoryDataOne) ? $categoryDataOne : (object)[];
        $list['data']     = !empty($data) ? $data : [];

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $list);
    }

    /**
     * 根据文章id获取文章详情
     * @return array
     * @author rjl
     */
    public function actionArticleDetail()
    {
        userLog(3, 2, '根据文章id获取文章详情');
        $id = $this->get('id', null); //文章id
        if (empty($id)) {
            return ResponseService::response(ErrorService::NEWS_ID_NO_EMPTY);
        }
        //判断文章是否存在
        $isNews = NewsManageService::isNews($id);
        if (!$isNews) {
            return ResponseService::apiResponse(ErrorService::NOT_FOUND_NEWS, '此数据不存在', (object)[]);
        }
        //获取文章详情
        $getArticleDetail = NewsManageService::getArticleDetail($id);

        if (empty($getArticleDetail)) {
            return ResponseService::apiResponse(ErrorService::STATUS_SUCCESS, '暂无数据', (object)[]);
        }
        //获取文章详情数据
        $data = NewsManageService::getArticleDetailData($getArticleDetail);
        return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
    }


    //todo 添加收藏 待定
    public function actionAddCollection()
    {
//        return $this->message('400', '功能待定', []);
    }

    //todo 获取收藏 待定
    public function actionGetCollection()
    {
//        return $this->message('400', '功能待定', []);
    }

    /**
     * 根据文章id获取视频详情
     * @return array
     */
    public function actionGetVideoDetails()
    {
        userLog(3, 2, '根据文章id获取视频详情');
        $id = $this->get('id', null); //文章id

        //文章id
        if (empty($id)) {
            return ResponseService::response(ErrorService::NEWS_ID_NO_EMPTY);
        }

        //判断文章是否存在
        $isNews = NewsManageService::isNews($id);
        if (!$isNews) {
            return ResponseService::apiResponse(ErrorService::NOT_FOUND_NEWS, '此数据不存在', (object)[]);
        }
        //获取视频详情
        $getVideoDetail = NewsManageService::getVideoDetail($id);

        if (empty($getVideoDetail)) {
            return ResponseService::apiResponse(ErrorService::STATUS_SUCCESS, '暂无数据', (object)[]);
        }

        $data = NewsManageService::getVideoDetailData($getVideoDetail);

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
    }

    /**
     * 根据文章id获取音频详情
     * @return array
     */
    public function actionGetAudioDetails()
    {
        userLog(3, 2, '根据文章id获取音频详情');
        $id = $this->get('id', null); //文章id
        if (empty($id)) {
            return ResponseService::response(ErrorService::NEWS_ID_NO_EMPTY);
        }
        $isNews = NewsManageService::isNews($id);
        if (!$isNews) {
            return ResponseService::apiResponse(ErrorService::NOT_FOUND_NEWS, '此数据不存在', (object)[]);
        }
        $getDetails = NewsManageService::getAudioDetails($id);
        if (empty($getDetails)) {
            return ResponseService::apiResponse(ErrorService::STATUS_SUCCESS, '暂无数据', (object)[]);
        }

        $data = NewsManageService::getAudioDetailData($getDetails);

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
    }


    /**
     * 获取专题列表
     * @return array|\yii\web\Response
     * @author rjl
     */
    public function actionSpeciaList()
    {
        userLog(3, 2, '获取专题列表');

        $id         = $this->get('id', null); //专题id
        $this->sidx = 'top_doing desc, listorder desc, updated_at desc, created_at';//排序

        //判断专题是否存在
        if (!XportalSpecial::findOne($id)) {
            return ResponseService::apiResponse(ErrorService::CONTENT_EMPTY, '此数据不存在', (object)[]);
        }

        $xportalSpecial = XportalSpecial::find()->select(['id', 'title', 'description', 'shuffling'])->where(['id' => $id, 'status' => 1, 'index_is_menu' => 1, 'chanel_is_menu' => 1, 'is_del' => 0, 'is_open' => 1])->asArray()->one();

        $data = [];
        if ($xportalSpecial) {
            $xportalSpecial['shuffling'] = getVar('FILEURL') . $xportalSpecial['shuffling'];
            //获取专题列表
            $query = NewsManageService::getSpecialList($xportalSpecial);
            $data  = $this->getJqTableData($query, "");
            if (!empty($data)) {
                $data = NewsManageService::getSpecialListData($data);
            }
        }

        //组装数据
        $list             = [];
        $list['category'] = !empty($xportalSpecial) ? $xportalSpecial : (object)[];
        $list['data']     = !empty($data) ? $data : [];

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $list);
    }

    /**
     * 获取相关推荐
     * @author rjl
     */
    public function actionRelated()
    {
        userLog(3, 2, '获取相关推荐');
        $id = $this->get('id', null);
        //id为空
        if (empty($id)) {
            return ResponseService::response(ErrorService::NEWS_ID_NO_EMPTY);
        }

        $type = $this->get('type', null);
        if (empty($type)) {
            return ResponseService::response(ErrorService::TYPE_EMPTY);
        }
        //查询类型是否存在
        if (!in_array($type, [1, 2])) {
            return ResponseService::response(ErrorService::TYPE_ERROR);
        }

        //判断文章是否存在
        if (!XportalNews::findOne($id)) {
            return ResponseService::apiResponse(ErrorService::NOT_FOUND_NEWS, '此数据不存在', (object)[]);
        }

        $news = XportalNews::find()->where(['id' => $id, 'is_del' => 0, 'status' => $this->portalnews])->one();
        if (empty($news)) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS, (object)[]);
        }

        if ($type == 1) {
            $num = getVar('APPRELATEDNUM', '3');//APP相关数量
        } elseif ($type == 2) {
            $num = getVar('PCRELATEDNUM', '3');//PC相关数量
        } else {
            $num = 10;
        }

        //如果是直播
        if ($news->news_type_id == 5) {
            //直播相关推荐
            $dataList = NewsManageService::getRelatedLiveList($news, $id, $num);
        } else {
            //相关推荐
            $dataList = NewsManageService::getRelatedList($news, $id, $num);
        }

        //给图片加上全域名
        $dataList = NewsManageService::getRelatedListData($dataList);

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $dataList);
    }

    /**
     * 获取协议
     * @author rjl
     */
    public function actionAgreement()
    {
        userLog(3, 2, '获取协议');
        $data = XportalSinglePage::find()->select(['single_content'])->where(['single_type_id' => 17, 'is_del' => 0])->asArray()->one();
        $data = !empty($data) ? $data : [];
        return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
    }

    /**
     * 压力测试
     * @return array
     */
    public function actionPressureTest()
    {
        userLog(3, 2, '压力测试');
        return ResponseService::response(ErrorService::STATUS_SUCCESS);
    }


}
