<?php
namespace api\modules\v1\controllers;

use Yii;
use backend\modules\xportal\models\XportalNewsShuffling;
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
use yii\db\Expression;

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
        $this->portalnews = getVar('PORTALNEWS'); //已发布标识
        $this->banner_limit = getVar('BANNERLIMIT'); //banner中limit数量
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
        $id = Yii::$app->request->get('id', null); //频道id和栏目id
        //类型为空
        if (empty($type)) {
            return ResponseService::response(ErrorService::TYPE_EMPTY);
        }
        //查询类型是否存在
        if (!in_array($type, [1,2,3])) {
            return ResponseService::response(ErrorService::TYPE_ERROR);
        }

        //查询关联轮播和文章关系
        $getNewsShuffling = XportalNewsShuffling::find()->from(XportalNewsShuffling::tableName() . ' shuffling')
            ->select(['news.id', 'news.title', 'news.shuffling','news.news_type_id'])
            ->leftJoin('xportal_news as news', 'shuffling.news_id = news.id');

        //根据类型组装查询条件
        $where = [];
        if ($type == 1) {
            $where = ['shuffling.is_del' => 0, 'shuffling.shuffing_type' => $type, 'news.is_del' => 0];
        }elseif ($type == 2 && !empty($id)) {
            $where = ['shuffling.is_del' => 0, 'shuffling.shuffing_type' => $type, 'shuffling.channeid' => $id, 'news.is_del' => 0];
        } elseif ($type == 3 && !empty($id)) {
            $where = ['shuffling.is_del' => 0, 'shuffling.shuffing_type' => $type, 'shuffling.catid' => $id, 'news.is_del' => 0];
        }

        //查询条件不能为空
        if (empty($where)) {
            return ResponseService::response(ErrorService::PARAMETER_ERROR);
        }

        $data = $getNewsShuffling->where($where)
            ->orderBy('shuffling.position desc')
            ->limit($this->banner_limit)->asArray()->all();
        //给图片加上全局域名
        foreach ($data as $k => $v) {
            if ($v['shuffling']) {
                $data[$k]['shuffling'] = getVar('FILEURL') . $v['shuffling'];
            }
        }

        if (empty($data)) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        }
        return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
    }


    /**
     * 获取推荐文章
     * @return array
     * @author rjl
     */
    public function actionRecommendArticle()
    {
        userLog(3, 2, '获取推荐文章');
        $type = Yii::$app->request->get('type', 1); //类型
        $id   = Yii::$app->request->get('id', null); //频道id和栏目id
        $newsType = $this->get('news_type', null); //资源库类型

        $pageSize = $this->getPage();
        $page     = $this->get('page', 1); //页数
        $sortord  = $this->get('sortord', 'top_doing desc, listorder desc, updated_at desc, created_at'); //排序
        $offset   = ($page - 1) * $pageSize;

        //类型为空
        if (empty($type)) {
            return ResponseService::response(ErrorService::TYPE_EMPTY);
        }
        //查询类型是否存在
        if (!in_array($type, [1, 2, 3])) {
            return ResponseService::response(ErrorService::TYPE_ERROR);
        }

        //附属标识
        $parameter = $this->get('parameter', 'project');
        $getChannelOne = XportalChannel::find()->from('xportal_channel')->select(['channel_id'])->Where(['is_del' => 0, 'parameter' => $parameter])->asArray()->one();
        
        //根据类型组装查询条件
        $where = [];
        if ($type == 1) {
            $where = ['seniority.is_del' => 0, 'seniority.shuffing_type' => $type];
        } elseif ($type == 2 && !empty($id)) {
            $where = ['seniority.is_del' => 0, 'seniority.shuffing_type' => $type, 'seniority.channeid' => $id];
        } elseif ($type == 3 && !empty($id)) {
            $where = ['seniority.is_del' => 0, 'seniority.shuffing_type' => $type, 'seniority.catid' => $id];
        }

        if (!empty($newsType) && !empty($where)) {
            $newsTypeWhere = ['news_type_id' => $newsType];
            //合并数组
            $where = array_merge_recursive($where, $newsTypeWhere);
        }

        //查询条件不能为空
        if (empty($where)) {
            return ResponseService::response(ErrorService::PARAMETER_ERROR);
        }

        //查询关联轮播和文章关系
        $getNewsList = XportalNewsSeniority::find()->from(XportalNewsSeniority::tableName() . ' seniority')
        ->select(['news.id', 'news.catid', 'news.channelid', 'news.title', 'news.created_at', 'news.copyfrom', 'news.shuffling', 'news.thumbnail',  'news.news_type_id',  'news.news_movie_uir', 'news.shuffling_index'])
        ->leftJoin('xportal_news as news', 'seniority.news_id = news.id')->where($where);
        
        if (empty($getNewsList)) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        }

        $this->sidx = $sortord;
        
        $data = $this->getPageSize($getNewsList, $offset);
        
        //给图片加上全局域名
        foreach ($data as $k => $v) {
            if ($v['thumbnail']) {
                $data[$k]['thumbnail'] = getVar('FILEURL') . $v['thumbnail'];
            }
            if ($v['shuffling']) {
                $data[$k]['shuffling'] = getVar('FILEURL') . $v['shuffling'];
            }
            //统计视频有几节课
            $data[$k]['news_movie_uir_count'] = 0;
            $data[$k]['news_video_length']    = 0;
            $data[$k]['news_movie_uir']       = '';
            if (isset($v['news_movie_uir'])) {
                $movie_uir                        = json_decode($v['news_movie_uir'], true);
                $data[$k]['news_movie_uir']       = $movie_uir;
                $data[$k]['news_movie_uir_count'] = count((array)$movie_uir);
                $data[$k]['news_video_length']    = $movie_uir[0]['video_length'];
            }

            //如果和专题匹配上打上标识
            $categoryBindData = XportalCategoryBind::find()->from('xportal_category_bind')->select('category_id')->where(['is_del' => 0, 'parentid' => $getChannelOne['channel_id']])->column();

            //判断是否是专题
            $data[$k]['project_mark'] = in_array($v['catid'], $categoryBindData) ? 1 : 0;
            //1大图 0小图
            $data[$k]['shuffling_index'] = $v['shuffling_index'] == 0 ? 0 : 1;

            //查询图集个数
            $data[$k]['news_image_count'] = 0;
            if ($v['news_type_id'] == 2) {
                $getNewsImageCount = XportalNewsImage::find()->from(XportalNewsImage::tableName())->where(['news_id' => $v['id'], 'news_image_visible' => 1, 'is_del' => 0])->count();
                $data[$k]['news_image_count'] = $getNewsImageCount;
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
        $pageSize = $this->getPage();

        $type     = $this->get('type', null); //类型
        $id       = $this->get('id', null); //频道id和栏目id
        $newsType = $this->get('news_type', null); //资源库类型
        $page     = $this->get('page', 1); //页数
        $sortord  = $this->get('sortord', 'top_doing desc, listorder desc, updated_at desc, created_at'); //排序
        $offset   = ($page - 1) * $pageSize;
        
        //类型为空
        if (empty($type)) {
            return ResponseService::response(ErrorService::TYPE_EMPTY);
        }
        //查询类型是否存在
        if (!in_array($type, [1, 2])) {
            return ResponseService::response(ErrorService::TYPE_ERROR);
        }

        //根据类型组装查询条件
        $where      = [];
        if ($type == 1 && !empty($id)) {
            //判断频道是否存在
            if (!XportalChannel::findOne($id)) {
                return ResponseService::apiResponse(ErrorService::CHANNEL_NO_EMPTY, '此数据不存在', (object)[]);
            }

            $where = ['is_del' => 0, 'status' => $this->portalnews, 'channelid' => $id];
        } elseif ($type == 2 && !empty($id)) {
            //判断栏目是否存在
            if (!XportalCategory::findOne($id)) {
                return ResponseService::apiResponse(ErrorService::CATEGORY_NO_EMPTY, '此数据不存在', (object)[]);
            }

            $where = ['is_del' => 0, 'status' => $this->portalnews, 'catid' => $id];
        }

        //查询条件不能为空
        if (empty($where)) {
            return ResponseService::response(ErrorService::PARAMETER_ERROR);
        }

        //如果是直播走特定方法
        if (!empty($newsType) && $newsType == 5) {
            $getLiveList = $this->actionGetLiveList($type,$id,$newsType,$page,$sortord,$offset);
            return $getLiveList;
        }

        $specialWhere = new Expression("FIND_IN_SET(:in_chanel_id_{$id}, in_chanel_id)", [":in_chanel_id_{$id}" => $id]);
        $xportalSpecial = XportalSpecial::find()->select(['id', 'title', 'banner'])->where($specialWhere)->andWhere(['status' => 1, 'index_is_menu' => 1, 'chanel_is_menu' => 1, 'is_del' => 0,'is_open' => 1])->limit(1)->offset($page - 1)->orderBy('list_order desc')->asArray()->one();
        if ($xportalSpecial) {
            $xportalSpecial['banner'] = getVar('FILEURL') . $xportalSpecial['banner'];
            $specialId = $xportalSpecial['id'];
            $newsWhere = new Expression("FIND_IN_SET(:subject_id{$specialId}, subject_id)", [":subject_id{$specialId}" => $specialId]);
            $getNewsListThree = XportalNews::find()->from(XportalNews::tableName())->select(['id', 'catid', 'channelid', 'title', 'created_at', 'news_type_id'])->where($newsWhere)->andWhere(['is_del' => 0, 'status' => $this->portalnews, 'channelid' => $id])->orderBy('top_doing desc, listorder desc, updated_at desc, created_at')->limit('3')->asArray()->all();
            $xportalSpecial['data'] = $getNewsListThree;
        }

        //栏目信息
        $category = [];
        if ($type == 2 && !empty($id)) {
            $category = XportalCategory::find()->select(['catid', 'catname', 'surface_plot'])->Where(['catid' => $id, 'ismenu' => 1, 'is_del' => 0])->asArray()->one();
            if ($category['surface_plot']) {
                $category['surface_plot'] = getVar('FILEURL') . $category['surface_plot'];
            }
        }

        if (empty($newsType)) {
            $getNewsList = XportalNews::find()->from(XportalNews::tableName())->select(['id', 'catid', 'channelid', 'title', 'created_at', 'copyfrom', 'shuffling', 'thumbnail', 'news_type_id', 'news_movie_uir','shuffling_index','islink','yes_no_islink','subject_id'])->where($where);
        } else {
            $getNewsList = XportalNews::find()->from(XportalNews::tableName())->select(['id', 'catid', 'channelid', 'title', 'created_at', 'copyfrom', 'shuffling', 'thumbnail', 'news_type_id', 'news_movie_uir','shuffling_index','islink','yes_no_islink','subject_id'])->where($where)->andWhere(['news_type_id' => $newsType]);
        }

        $this->sidx = $sortord;
        
        $data = $this->getPageSize($getNewsList, $offset);

        if (empty($data)) {
            $object = [];
            $object['special'] = (object)[];
            $object['category'] = (object)$category;
            $object['data'] = [];
            return ResponseService::apiResponse(ErrorService::STATUS_SUCCESS, '暂无数据', (object)[]);
//            return ResponseService::response(ErrorService::STATUS_SUCCESS, $object);
        }
        
        foreach ($data as $k => $v) {
            if ($v['thumbnail']) {
                $data[$k]['thumbnail'] = getVar('FILEURL') . $v['thumbnail'];
            }

            if ($v['shuffling']) {
                $data[$k]['shuffling'] = getVar('FILEURL') . $v['shuffling'];
            }
            //统计视频有几节课
            $data[$k]['news_movie_uir_count'] = 0;
            $data[$k]['news_video_length'] = 0;
            if (isset($v['news_movie_uir'])) {
                $movie_uir = json_decode($v['news_movie_uir'], true);
                $data[$k]['news_movie_uir'] = $movie_uir;
                if (!empty($data[$k]['news_movie_uir'])) {
                    foreach ($data[$k]['news_movie_uir'] as $key => $value) {
                        $data[$k]['news_movie_uir'][$key]['file']                    = getVar('FILEURL') . $value['file'];
                    }
                    $data[$k]['news_video_length'] = $movie_uir[0]['video_length'];
                }
                
                $data[$k]['news_movie_uir_count'] = count((array)$movie_uir);
            }

            //查询图集个数
            $data[$k]['news_image_count'] = 0;
            if ($v['news_type_id'] == 2) {
                $getNewsImageCount = XportalNewsImage::find()->from(XportalNewsImage::tableName())->where(['news_id' => $v['id'], 'news_image_visible' => 1, 'is_del' => 0])->count();
                $data[$k]['news_image_count'] = $getNewsImageCount;
            }

            //判断是否是专题
            $data[$k]['project_mark'] = $v['subject_id'] ? 1 : 0;
            //1大图 0小图
            $data[$k]['shuffling_index'] = $v['shuffling_index'] == 0 ? 0 : 1;
        }

        //组装数据
        $list = [];
        $list['special'] = !empty($xportalSpecial) ? $xportalSpecial : (object)[];
        $list['category'] = !empty($category) ? $category : (object)[];
        $list['data'] = !empty($data) ? $data : [];

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $list);
    }

    /**
     * 直播列表
     * @return array
     * @author rjl 
     */
    public function actionGetLiveList($type, $id = '', $newsType, $page, $sortord, $offset)
    {
        //根据类型组装查询条件
        $time = time();
        $where      = [];
        if ($type == 1 && !empty($id)) {
            $where = ['n.channelid' => $id];
        } elseif ($type == 2 && !empty($id)) {
            $where = ['n.catid' => $id];
        }
        //查询关联直播和文章关系
        $getNewsList = XportalNews::find()->from(XportalNews::tableName() . ' n')->select(['n.id', 'n.catid', 'n.channelid', 'n.title', 'n.created_at', 'n.updated_at', 'n.copyfrom', 'n.shuffling', 'n.thumbnail', 'n.news_type_id', 'n.news_movie_uir', 'n.shuffling_index', 'n.islink', 'n.yes_no_islink','n.subject_id'])
        ->leftJoin('xportal_news_live as l', 'n.id = l.news_id')->where($where)->andWhere(['n.news_type_id' => $newsType, 'n.is_del' => 0, 'n.status' => $this->portalnews, 'l.is_del' => 0])->andWhere(['<', 'l.start_time', $time])->andWhere(['>', 'l.end_time', $time]);
        $this->sidx = $sortord;

        $data = $this->getPageSize($getNewsList, $offset);

        if (empty($data)) {
            $object = [];
            $object['category'] = (object)[];
            $object['data'] = [];
            
            return ResponseService::apiResponse(ErrorService::STATUS_SUCCESS, '暂无数据', $object);
        }

        foreach ($data as $k => $v) {
            if ($v['thumbnail']) {
                $data[$k]['thumbnail'] = getVar('FILEURL') . $v['thumbnail'];
            }

            if ($v['shuffling']) {
                $data[$k]['shuffling'] = getVar('FILEURL') . $v['shuffling'];
            }
            //统计视频有几节课
            $data[$k]['news_movie_uir_count'] = 0;
            $data[$k]['news_video_length'] = 0;
            if (isset($v['news_movie_uir'])) {
                $movie_uir = json_decode($v['news_movie_uir'], true);
                
                $data[$k]['news_movie_uir'] = $movie_uir;
                foreach ($data[$k]['news_movie_uir'] as $key => $value) {
                    $data[$k]['news_movie_uir'][$key]['file']                    = getVar('FILEURL') . $value['file'];
                }
                if (!empty($movie_uir)) {
                    $data[$k]['news_movie_uir_count'] = count((array)$movie_uir);
                    $data[$k]['news_video_length']    = $movie_uir[0]['video_length'];
                }
            }

            //查询图集个数
            $data[$k]['news_image_count'] = 0;
            if ($v['news_type_id'] == 2) {
                $getNewsImageCount = XportalNewsImage::find()->from(XportalNewsImage::tableName())->where(['news_id' => $v['id'], 'news_image_visible' => 1, 'is_del' => 0])->count();
                $data[$k]['news_image_count'] = $getNewsImageCount;
            }

            //判断是否是专题
            $data[$k]['project_mark'] = $v['subject_id'] ? 1 : 0;
            //1大图 0小图
            $data[$k]['shuffling_index'] = $v['shuffling_index'] == 0 ? 0 : 1;
        }

        //组装数据
        $list = [];
        $list['category'] = !empty($categoryDataOne) ? $categoryDataOne : (object)[];
        $list['data'] = !empty($data) ? $data : [];

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
        $id   = $this->get('id', null); //文章id
        //类型为空
        if (empty($id)) {
            return ResponseService::response(ErrorService::NEWS_ID_NO_EMPTY);
        }

        //判断文章是否存在
        if (!XportalNews::findOne($id)) {
            return ResponseService::apiResponse(ErrorService::NOT_FOUND_NEWS, '此数据不存在', (object)[]);
        }

        $data = XportalNews::find()->from(XportalNews::tableName())
            ->select(['id', 'catid', 'channelid', 'subject_order', 'subject_id', 'title_eyebrow', 'title', 'title_sub', 'content', 'author', 'keywords', 'listorder', 'movie_blankurl', 'relation', 'allow_comment', 'copyfrom', 'islink', 'yes_no_islink', 'click_number', 'index_listorder', 'channel_listorder', 'is_image', 'cache', 'shuffling', 'thumbnail', 'qr_code', 'shuffling_channel', 'news_uploadfile', 'news_type_id', 'news_uploadfile_describe', 'news_movie_uir', 'news_movie_uir_describe', 'top_doing', 'updated_at', 'created_at'])
            ->Where(['id' => $id, 'is_del' => 0, 'status' => $this->portalnews])
            ->asArray()
            ->one();
        if (empty($data)) {
//            return ResponseService::response(ErrorService::STATUS_SUCCESS, (object)[]);
            return ResponseService::apiResponse(ErrorService::STATUS_SUCCESS, '暂无数据', (object)[]);
        }

        //点击量
        $model = XportalNews::findOne($data['id']);
        $model->click_number = $data['click_number'] + 1;
        $model->save();
        
//        if(!empty($data['created_at'])){
//            $data['created_at'] = date("m-d", $data['created_at']);
//        }
        
        if(!empty($data['shuffling'])){
            $data['shuffling'] = getVar('FILEURL').$data['shuffling'];//轮播图    
        }

        if(!empty($data['thumbnail'])){
            $data['thumbnail'] = getVar('FILEURL').$data['thumbnail'];//缩略图
        }
        
        $data['content'] = $this->add_domain_url_str($data['content']);//替换内容里图片链接

        //直播
        if (!empty($data['movie_blankurl'])) {
            $data['movie_blankurl']       = json_decode($data['movie_blankurl'], true);
        } else {
            $data['movie_blankurl'] = [];
        }

        //给视频加上全局域名
        if (!empty($data['news_movie_uir'])) {
            $data['news_movie_uir']       = json_decode($data['news_movie_uir'], true);
            $data['news_movie_uir_count'] = count($data['news_movie_uir']);
            foreach ($data['news_movie_uir'] as $k => $v) {
                if ($v['status'] == 1) {
                    $data['news_movie_uir'][$k]['file']                    = getVar('FILEURL') . $v['file'];
                    if(!empty($v['transcoding'])){
                        foreach($v['transcoding'] as $ke => $ve){
                            $data['news_movie_uir'][$k]['transcoding'][$ke]['url'] = getVar('FILEURL').$ve['url'];
                        }
                    } else {
                        $data['news_movie_uir'][$k]['transcoding'] = [];
                    }
                }
            }
        }

        $getNewsImageList = XportalNewsImage::find()->from(XportalNewsImage::tableName())->select(['id','news_image_author','newsl_image_caption','news_image_name','news_image_url','created_id','created_at'])->where(['news_id' =>$data['id'],'news_image_visible' => 1, 'is_del' => 0])->orderBy('position desc')->asArray()->all();

        if ($getNewsImageList){
            foreach ($getNewsImageList as $key => $value){
                $data['image'][$key]['id']                  = $value['id'];
                $data['image'][$key]['news_image_author']   = $value['news_image_author'];
                $data['image'][$key]['newsl_image_caption'] = $value['newsl_image_caption'];
                $data['image'][$key]['news_image_name']     = $value['news_image_name'];
                $data['image'][$key]['news_image_url']      = getVar('FILEURL').$value['news_image_url'];
                $data['image'][$key]['created_id']          = $value['created_id'];
                $data['image'][$key]['created_at']          = $value['created_at'];
            }
        } else {
            $data['image'] = [];
        }

        $parameter = $this->get('parameter', 'project'); //附属标识

        $getChannelOne = XportalChannel::find()->from('xportal_channel')->select(['channel_id'])->Where(['is_del' => 0, 'parameter' => $parameter])->asArray()->one();

        //如果和专题匹配上打上标识
        $categoryBindData = XportalCategoryBind::find()->from('xportal_category_bind')->select('category_id')->where(['is_del' => 0, 'parentid' => $getChannelOne['channel_id']])->column();

        $data['project_mark'] = 0;
        if (in_array($data['catid'], $categoryBindData)) {
            $data['project_mark'] = 1;
        }

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
    }


    //todo 添加收藏 待定
    public function actionAddCollection()
    {
        return $this->message('400', '功能待定', []);
    }

    //todo 获取收藏 待定
    public function actionGetCollection()
    {
        return $this->message('400', '功能待定', []);
    }

    /**
     * 根据文章id获取视频详情
     * @return array
     */
    public function actionGetVideoDetails()
    {
        userLog(3, 2, '根据文章id获取视频详情');
        $id = $this->get('id', null); //文章id
        //类型为空
        if (empty($id)) {
            return ResponseService::response(ErrorService::NEWS_ID_NO_EMPTY);
        }

        //判断文章是否存在
        if (!XportalNews::findOne($id)) {
            return ResponseService::apiResponse(ErrorService::NOT_FOUND_NEWS, '此数据不存在', (object)[]);
        }

        $data = XportalNews::find()->from(XportalNews::tableName())
            ->select(['id', 'title', 'content', 'author', 'movie_blankurl', 'copyfrom', 'click_number', 'islink', 'yes_no_islink', 'thumbnail', 'news_movie_uir', 'created_id', 'created_at'])
            ->Where(['id' => $id, 'is_del' => 0, 'status' => $this->portalnews, 'news_type_id' => 3])->asArray()->one();

        if (empty($data)) {
//            return ResponseService::response(ErrorService::STATUS_SUCCESS, (object)[]);
            return ResponseService::apiResponse(ErrorService::STATUS_SUCCESS, '暂无数据', (object)[]);
        }
        //给图片加上全局域名
        if(!empty($data['thumbnail'])){
            $data['thumbnail'] = getVar('FILEURL').$data['thumbnail'];
        }

        //替换内容里图片链接
        if(!empty($data['content'])){
            $data['content'] = $this->add_domain_url_str($data['content']);
        }

        //判断是否启用外部链接
        if ($data['news_movie_uir'] == 0 && !empty($data['news_movie_uir'])) {
            $data['news_movie_uir']= json_decode($data['news_movie_uir'], true);
            foreach ($data['news_movie_uir'] as $k => $v) {
                if ($v['status'] == 1) {
                    $data['news_movie_uir'][$k]['file']                    = getVar('FILEURL') . $v['file'];
                    if(!empty($v['transcoding'])){
                        foreach($v['transcoding'] as $ke => $ve){
                            $data['news_movie_uir'][$k]['transcoding'][$ke]['url'] = getVar('FILEURL').$ve['url'];
                        }
                    } else {
                        $data['news_movie_uir'][$k]['transcoding'] = [];
                    }
                }

            }
            $data['movie'] = $data['news_movie_uir'];
            unset($data['news_movie_uir']);
        }

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
        //类型为空
        if (empty($id)) {
            return ResponseService::response(ErrorService::NEWS_ID_NO_EMPTY);
        }

        //判断文章是否存在
        if (!XportalNews::findOne($id)) {
            return ResponseService::apiResponse(ErrorService::NOT_FOUND_NEWS, '此数据不存在', (object)[]);
        }

        $data = XportalNews::find()->from(XportalNews::tableName())
            ->select(['id', 'title', 'content', 'author', 'movie_blankurl', 'copyfrom', 'click_number', 'islink', 'yes_no_islink', 'thumbnail', 'news_movie_uir', 'created_id', 'created_at'])
            ->Where(['id' => $id, 'is_del' => 0, 'status' => $this->portalnews, 'news_type_id' => 6])->asArray()->one();

        if (empty($data)) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS, (object)[]);
        }

        //替换内容里图片链接
        if(!empty($data['content'])){
            $data['content'] = $this->add_domain_url_str($data['content']);
        }

        //给图片加域名
        if (!empty($data['thumbnail'])) {
            $data['thumbnail'] = getVar('FILEURL') . $data['thumbnail'];
        }

        //判断是否启用外部链接
        if ($data['news_movie_uir'] == 0 && !empty($data['news_movie_uir'])) {
            $audio = json_decode($data['news_movie_uir'], true);
            foreach ($audio as $k => $v) {
                if ($v['status'] == 1) {
                    $data['audio'][$k]['file']                    = getVar('FILEURL') . $v['file'];
                    $data['audio'][$k]['describe']                = $v['describe'];
                    $data['audio'][$k]['fileSize']                = $v['fileSize'];
                    $data['audio'][$k]['fileName']                = $v['fileName'];
                }
            }
            unset($data['news_movie_uir']);
        }

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
        $pageSize = $this->getPage();

        $id       = $this->get('id', null); //频道id和栏目id
        $page     = $this->get('page', 1); //页数
        $sortord  = $this->get('sortord', 'top_doing desc, listorder desc, updated_at desc, created_at'); //排序
        $offset   = ($page - 1) * $pageSize;

        //判断专题是否存在
        if (!XportalSpecial::findOne($id)) {
            return ResponseService::apiResponse(ErrorService::CONTENT_EMPTY, '此数据不存在', (object)[]);
        }

        $xportalSpecial = XportalSpecial::find()->select(['id', 'title', 'description', 'shuffling'])->where(['id' => $id, 'status' => 1, 'index_is_menu' => 1, 'chanel_is_menu' => 1, 'is_del' => 0,'is_open' => 1])->asArray()->one();
        
        $data = [];
        if ($xportalSpecial) {
            $xportalSpecial['shuffling'] = getVar('FILEURL') . $xportalSpecial['shuffling'];
            
            $specialId = $xportalSpecial['id'];
            $newsWhere = new Expression("FIND_IN_SET(:subject_id{$specialId}, subject_id)", [":subject_id{$specialId}" => $specialId]);

            $getNewsList = XportalNews::find()->from(XportalNews::tableName())->select(['id', 'subject_id', 'catid', 'shuffling_index', 'channelid', 'title', 'created_at', 'thumbnail', 'news_type_id', 'shuffling'])->where($newsWhere)->andWhere(['is_del' => 0, 'status' => $this->portalnews]);

            $this->sidx = $sortord;
            $data = $this->getPageSize($getNewsList, $offset);
            if (!empty($data)) {
                foreach ($data as $k => $v) {
                    if ($v['thumbnail']) {
                        $data[$k]['thumbnail'] = getVar('FILEURL') . $v['thumbnail'];
                    }
        
                    if ($v['shuffling']) {
                        $data[$k]['shuffling'] = getVar('FILEURL') . $v['shuffling'];
                    }
                    //统计视频有几节课
                    $data[$k]['news_movie_uir_count'] = 0;
                    $data[$k]['news_video_length']    = 0;
                    if (isset($v['news_movie_uir'])) {
                        $movie_uir                  = json_decode($v['news_movie_uir'], true);
                        $data[$k]['news_movie_uir'] = $movie_uir;
                        foreach ($data[$k]['news_movie_uir'] as $key => $value) {
                            $data[$k]['news_movie_uir'][$key]['file'] = getVar('FILEURL') . $value['file'];
                        }
                        $data[$k]['news_movie_uir_count'] = count((array)$movie_uir);
                        $data[$k]['news_video_length']    = $movie_uir[0]['video_length'];
                    }
        
                    //查询图集个数
                    $data[$k]['news_image_count'] = 0;
                    if ($v['news_type_id'] == 2) {
                        $getNewsImageCount = XportalNewsImage::find()->from(XportalNewsImage::tableName())->where(['news_id' => $v['id'], 'news_image_visible' => 1, 'is_del' => 0])->count();
                        $data[$k]['news_image_count'] = $getNewsImageCount;
                    }
        
                    //判断是否是专题
                    $data[$k]['project_mark'] = $v['subject_id'] ? 1 : 0;
                    //1大图 0小图
                    $data[$k]['shuffling_index'] = $v['shuffling_index'] == 0 ? 0 : 1;
                }
                // return ResponseService::response(ErrorService::STATUS_SUCCESS);
            }
        }

        //组装数据
        $list = [];
        $list['category'] = !empty($xportalSpecial) ? $xportalSpecial : (object)[];
        $list['data'] = !empty($data) ? $data : [];

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $list);
    }

    /**
     * 获取相关推荐
     * @author rjl
     */
    public function actionRelated()
    {
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

        $news = XportalNews::find()->where(['id' => $id, 'is_del' => 0,'status' => $this->portalnews])->one();
        if (empty($news)) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS, (object)[]);
        }

        if ($type == 1) {
            $num = getVar('APPRELATEDNUM','3');//APP相关数量
        } elseif ($type == 2) {
            $num = getVar('PCRELATEDNUM','3');//PC相关数量
        }
        
        $dataList = [];

        //如果是直播
        $time = time();
        if ($news->news_type_id == 5) {
            $s = ['n.id', 'n.channelid', 'n.catid', 'n.title', 'n.news_type_id', 'n.thumbnail', 'n.created_at', 'n.news_movie_uir'];
            //按关键字匹配数据
            if (!empty($news->keywords)) {
                $keywords = explode(',', $news->keywords);//分割字符串
                //模糊匹配关键字
                $dataList = XportalNews::find()->from(XportalNews::tableName() . ' n')->select($s)->leftJoin('xportal_news_live as l', 'n.id = l.news_id')->where(['or like', 'n.title', $keywords])->andWhere(['n.catid' => $news->catid, 'n.is_del' => 0])->andWhere(['not in', 'n.id', $id])->andWhere(['n.status' => $this->portalnews])->andWhere(['<', 'l.start_time', $time])->andWhere(['>', 'l.end_time', $time])->limit('10')->asArray()->all();
            }
            //按栏目匹配数据
            if (count($dataList) < $num) {
                //返回数组中id的值
                $arrayColum = array_column($dataList, 'id');
                $arrayColum = array_merge($arrayColum, [$id]);
                //计算要获取的数量
                $remainingNum = $num - count($dataList);

                $dataCatidList = XportalNews::find()->from(XportalNews::tableName() . ' n')->select($s)->leftJoin('xportal_news_live as l', 'n.id = l.news_id')->where(['n.catid' => $news->catid, 'n.is_del' => 0])->andWhere(['not in', 'n.id', $arrayColum])->andWhere(['n.status' => $this->portalnews])->andWhere(['<', 'l.start_time', $time])->andWhere(['>', 'l.end_time', $time])->limit($remainingNum)->orderBy('n.listorder desc')->asArray()->all();
                $dataList      = array_merge($dataList, $dataCatidList);
            }
            //按频道匹配数据
            if (count($dataList) < $num) {
                //返回数组中id的值
                $arrayColum = array_column($dataList, 'id');
                $arrayColum = array_merge($arrayColum, [$id]);
                //计算要获取的数量
                $remainingNum = $num - count($dataList);

                $dataChannelList = XportalNews::find()->from(XportalNews::tableName() . ' n')->select($s)->leftJoin('xportal_news_live as l', 'n.id = l.news_id')->where(['n.channelid' => $news->channelid, 'n.is_del' => 0])->andWhere(['not in', 'n.id', $arrayColum])->andWhere(['n.status' => $this->portalnews])->andWhere(['<', 'l.start_time', $time])->andWhere(['>', 'l.end_time', $time])->limit($remainingNum)->orderBy('n.listorder desc')->asArray()->all();
                $dataList        = array_merge($dataList, $dataChannelList);
            }
        } else {
            $select = ['id', 'channelid', 'catid', 'title', 'news_type_id', 'thumbnail','created_at','news_movie_uir'];
            //按关键字匹配数据
            if (!empty($news->keywords)) {
                $keywords = explode(',', $news->keywords);//分割字符串
                //模糊匹配关键字
                $dataList = XportalNews::find()->select($select)->where(['or like', 'title', $keywords])->andWhere(['catid' => $news->catid, 'is_del' => 0])->andWhere(['not in', 'id', $id])->andWhere(['status' => $this->portalnews])->limit('10')->asArray()->all();
            }
            //按栏目匹配数据
            if (count($dataList) < $num) {
                //返回数组中id的值
                $arrayColum = array_column($dataList, 'id');
                $arrayColum = array_merge($arrayColum, [$id]);
                //计算要获取的数量
                $remainingNum = $num - count($dataList);

                $dataCatidList = XportalNews::find()->select($select)->where(['catid' => $news->catid, 'is_del' => 0])->andWhere(['not in', 'id', $arrayColum])->andWhere(['status' => $this->portalnews])->limit($remainingNum)->orderBy('listorder desc')->asArray()->all();
                $dataList = array_merge($dataList, $dataCatidList);
            }
            //按频道匹配数据
            if (count($dataList) < $num) {
                //返回数组中id的值
                $arrayColum = array_column($dataList, 'id');
                $arrayColum = array_merge($arrayColum, [$id]);
                //计算要获取的数量
                $remainingNum = $num - count($dataList);

                $dataChannelList = XportalNews::find()->select($select)->where(['channelid' => $news->channelid, 'is_del' => 0])->andWhere(['not in', 'id', $arrayColum])->andWhere(['status' => $this->portalnews])->limit($remainingNum)->orderBy('listorder desc')->asArray()->all();
                $dataList = array_merge($dataList, $dataChannelList);
            }
        }
        
        //给图片加上全域名
        foreach ($dataList as $k => $v) {
            //统计视频时长
            $dataList[$k]['news_movie_uir'] = null;
            if(!empty($v['thumbnail'])){
                $dataList[$k]['thumbnail'] = getVar('FILEURL').$v['thumbnail'];
            }
            if(!empty($v['shuffling'])){
                $dataList[$k]['shuffling'] = getVar('FILEURL').$v['shuffling'];
            }

            $dataList[$k]['news_video_length'] = 0;
            if (isset($v['news_movie_uir'])) {
                $movie_uir = json_decode($v['news_movie_uir'], true);
                if ($movie_uir) {
                    $dataList[$k]['news_movie_uir']    = $movie_uir;
                    $dataList[$k]['news_video_length'] = $movie_uir[0]['video_length'];
                }

                foreach ($movie_uir as $key => $value) {
                    if ($value['status'] == 1) {
                        $dataList[$k]['news_movie_uir'][$key]['file']                    = getVar('FILEURL') . $value['file'];
                    }
                }
            }

            //查询图集个数
            $dataList[$k]['news_image_count'] = 0;
            if ($v['news_type_id'] == 2) {
                $getNewsImageCount = XportalNewsImage::find()->from(XportalNewsImage::tableName())->where(['news_id' => $v['id'], 'news_image_visible' => 1, 'is_del' => 0])->count();
                $dataList[$k]['news_image_count'] = $getNewsImageCount;
            }
        }

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $dataList);
    }

    /**
     * 获取协议
     * @author rjl
     */
    public function actionAgreement()
    {
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
        return ResponseService::response(ErrorService::STATUS_SUCCESS);
    }

}
