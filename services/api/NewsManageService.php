<?php

namespace services\api;

use Yii;
use common\components\Service;

use backend\modules\xportal\models\XportalNews;
use backend\modules\xportal\models\XportalNewsImage;
use backend\modules\xportal\models\XportalChannel;
use backend\modules\xportal\models\XportalCategory;
use backend\modules\xportal\models\XportalNewsShuffling;
use backend\modules\xportal\models\XportalCategoryBind;
use backend\modules\xportal\models\XportalSpecial;
use yii\db\Expression;

/**
 * Class ApiService
 * @package services\api
 * author:rjl
 */
class NewsManageService extends Service
{
    /**
     * 获取轮播图
     * @param $type int 类型
     * @param $id   int 频道id和栏目id
     * @return array
     */
    static public function getBannerList($type, $id)
    {
        $channeId = $type == 2 && !empty($id) ? $id : '';
        $catId    = $type == 3 && !empty($id) ? $id : '';

        //查询关联轮播和文章关系
        $data = XportalNewsShuffling::find()->from(XportalNewsShuffling::tableName() . ' shuffling')
            ->select(['news.id', 'news.title', 'news.shuffling', 'news.news_type_id'])
            ->leftJoin('xportal_news as news', 'shuffling.news_id = news.id')
            ->where(['shuffling.is_del' => 0, 'shuffling.shuffing_type' => $type, 'news.is_del' => 0])->andFilterWhere(['shuffling.channeid' => $channeId])->andFilterWhere(['shuffling.catid' => $catId])
            ->orderBy('shuffling.position desc')
            ->limit(getVar('BANNERLIMIT'))->asArray()->all();
        return $data;
    }

    /**
     * 获取文章列表
     * @param $where array 查询条件
     * @param $newsType int 资源库类型
     * @return string
     */
    static public function getArticleList($where, $newsType)
    {
        $getNewsList = XportalNews::find()->from(XportalNews::tableName())->select(['id', 'catid', 'channelid', 'title', 'title_eyebrow', 'title_sub', 'author', 'keywords', 'relation', 'allow_comment', 'copyfrom', 'click_number', 'index_listorder', 'channel_listorder', 'shuffling', 'thumbnail', 'qr_code', 'news_type_id', 'news_movie_uir', 'shuffling_index', 'islink', 'yes_no_islink', 'subject_id', 'top_doing', 'created_at', 'updated_at'])->where($where)->andFilterWhere(['news_type_id' => $newsType]);

        return $getNewsList;
    }

    /**
     * 获取文章专题
     * @param $id int 频道id和栏目id
     * @param $page int 页数
     * @return array|null|\yii\db\ActiveRecord
     */
    static public function getSpecial($id, $page)
    {
        $specialWhere   = new Expression("FIND_IN_SET(:in_chanel_id_{$id}, in_chanel_id)", [":in_chanel_id_{$id}" => $id]);
        $xportalSpecial = XportalSpecial::find()->select(['id', 'title', 'banner'])->where($specialWhere)->andWhere(['status' => 1, 'index_is_menu' => 1, 'chanel_is_menu' => 1, 'is_del' => 0, 'is_open' => 1])->limit(1)->offset($page - 1)->orderBy('list_order desc')->asArray()->one();
        if ($xportalSpecial) {
            $xportalSpecial['banner'] = getVar('FILEURL') . $xportalSpecial['banner'];
            $specialId                = $xportalSpecial['id'];
            $newsWhere                = new Expression("FIND_IN_SET(:subject_id{$specialId}, subject_id)", [":subject_id{$specialId}" => $specialId]);
            $getNewsListThree         = XportalNews::find()->from(XportalNews::tableName())->select(['id', 'catid', 'channelid', 'title', 'created_at', 'news_type_id'])->where($newsWhere)->andWhere(['is_del' => 0, 'status' => getVar('PORTALNEWS'), 'channelid' => $id])->orderBy('top_doing desc, listorder desc, updated_at desc, created_at')->limit('3')->asArray()->all();
            $xportalSpecial['data']   = $getNewsListThree;
        }

        return $xportalSpecial;
    }

    /**
     * 获取文章栏目
     * @param $type int 类型
     * @param $id int 频道id和栏目id
     * @return array|null|\yii\db\ActiveRecord
     */
    static public function getCategory($type, $id)
    {
        $category = [];
        if ($type == 2 && !empty($id)) {
            $category = XportalCategory::find()->select(['catid', 'catname', 'surface_plot'])->Where(['catid' => $id, 'ismenu' => 1, 'is_del' => 0])->asArray()->one();
            if ($category['surface_plot']) {
                $category['surface_plot'] = getVar('FILEURL') . $category['surface_plot'];
            }
        }

        return $category;
    }

    /**
     * 获取文章列表组装文章数据
     * @param $data
     * @return mixed
     */
    static public function getNewsList($data)
    {
        foreach ($data as $k => $v) {
            if ($v['thumbnail']) {
                $data[$k]['thumbnail'] = getVar('FILEURL') . $v['thumbnail'];
            }

            if ($v['shuffling']) {
                $data[$k]['shuffling'] = getVar('FILEURL') . $v['shuffling'];
            }

            if ($v['qr_code']) {
                $data[$k]['qr_code'] = getVar('FILEURL') . $v['qr_code'];//二维码
            }

            //统计视频有几节课
            $data[$k]['news_movie_uir_count'] = 0;
            $data[$k]['news_video_length']    = 0;
            if (isset($v['news_movie_uir'])) {
                $movie_uir                  = json_decode($v['news_movie_uir'], true);
                $data[$k]['news_movie_uir'] = $movie_uir;
                if (!empty($data[$k]['news_movie_uir'])) {
                    foreach ($data[$k]['news_movie_uir'] as $key => $value) {
                        $data[$k]['news_movie_uir'][$key]['file'] = getVar('FILEURL') . $value['file'];
                    }
                    $data[$k]['news_video_length'] = $movie_uir[0]['video_length'];
                }

                $data[$k]['news_movie_uir_count'] = count((array)$movie_uir);
            }

            //查询图集个数
            $data[$k]['news_image_count'] = 0;
            if ($v['news_type_id'] == 2) {
                $getNewsImageCount            = XportalNewsImage::find()->from(XportalNewsImage::tableName())->where(['news_id' => $v['id'], 'news_image_visible' => 1, 'is_del' => 0])->count();
                $data[$k]['news_image_count'] = $getNewsImageCount;
            }

            //判断是否是专题
            $data[$k]['project_mark'] = $v['subject_id'] ? 1 : 0;
            //1大图 0小图
            $data[$k]['shuffling_index'] = $v['shuffling_index'] == 0 ? 0 : 1;
        }

        return $data;
    }


    /**
     * 获取直播数据
     * @param $type int 类型为空
     * @param $id int 频道id和栏目id
     * @param $newsType int 资源库类型
     * @return string
     */
    static public function getLiveList($type, $id, $newsType)
    {
        //根据类型组装查询条件
        $time  = time();
        $where = [];
        if ($type == 1 && !empty($id)) {
            $where = ['n.channelid' => $id];
        } elseif ($type == 2 && !empty($id)) {
            $where = ['n.catid' => $id];
        }
        //查询关联直播和文章关系
        $getNewsList = XportalNews::find()->from(XportalNews::tableName() . ' n')->select(['n.id','n.catid','n.channelid','n.title','n.title_eyebrow','n.title_sub','n.author','n.keywords','n.relation','n.allow_comment','n.copyfrom','n.click_number','n.index_listorder','n.channel_listorder','n.shuffling','n.thumbnail','n.qr_code','n.news_type_id','n.news_movie_uir','n.shuffling_index','n.islink','n.yes_no_islink','n.subject_id','n.top_doing','n.created_at','n.updated_at'])
            ->leftJoin('xportal_news_live as l', 'n.id = l.news_id')->where($where)->andWhere(['n.news_type_id' => $newsType, 'n.is_del' => 0, 'n.status' => getVar('PORTALNEWS'), 'l.is_del' => 0])->andWhere(['<', 'l.start_time', $time])->andWhere(['>', 'l.end_time', $time]);
        return $getNewsList;
    }


    /**
     * 获取文章详情
     * @param $id int 文章id
     * @return array
     */
    static public function getArticleDetail($id)
    {
        $data = XportalNews::find()->from(XportalNews::tableName())
            ->select(['id', 'catid', 'channelid', 'subject_order', 'subject_id', 'title_eyebrow', 'title', 'title_sub', 'content', 'author', 'keywords', 'listorder', 'movie_blankurl', 'relation', 'allow_comment', 'copyfrom', 'islink', 'yes_no_islink', 'click_number', 'index_listorder', 'channel_listorder', 'is_image', 'cache', 'shuffling', 'thumbnail', 'qr_code', 'shuffling_channel', 'news_uploadfile', 'news_type_id', 'news_uploadfile_describe', 'news_movie_uir', 'news_movie_uir_describe', 'top_doing', 'updated_at', 'created_at'])
            ->Where(['id' => $id, 'is_del' => 0, 'status' => getVar('PORTALNEWS')])->asArray()->one();

        return $data;
    }

    /**
     * 获取文章详情数据
     * @param $data
     */
    static public function getArticleDetailData($data)
    {
        //点击量
        $model               = XportalNews::findOne($data['id']);
        $model->click_number = $data['click_number'] + 1;
        $model->save();

        if (!empty($data['shuffling'])) {
            $data['shuffling'] = getVar('FILEURL') . $data['shuffling'];//轮播图
        }

        if (!empty($data['thumbnail'])) {
            $data['thumbnail'] = getVar('FILEURL') . $data['thumbnail'];//缩略图
        }

        if (!empty($data['qr_code'])) {
            $data['qr_code'] = getVar('FILEURL') . $data['qr_code'];//二维码
        }

        $data['content'] = self::urlIsStr($data['content']);//替换内容里图片链接

        //直播
        if (!empty($data['movie_blankurl'])) {
            $data['movie_blankurl'] = json_decode($data['movie_blankurl'], true);
        } else {
            $data['movie_blankurl'] = [];
        }

        //给视频加上全局域名
        if (!empty($data['news_movie_uir'])) {
            $data['news_movie_uir']       = json_decode($data['news_movie_uir'], true);
            $data['news_movie_uir_count'] = count($data['news_movie_uir']);
            foreach ($data['news_movie_uir'] as $k => $v) {
                if ($v['status'] == 1) {
                    $data['news_movie_uir'][$k]['file'] = getVar('FILEURL') . $v['file'];
                    if (!empty($v['transcoding'])) {
                        foreach ($v['transcoding'] as $ke => $ve) {
                            $data['news_movie_uir'][$k]['transcoding'][$ke]['url'] = getVar('FILEURL') . $ve['url'];
                        }
                    } else {
                        $data['news_movie_uir'][$k]['transcoding'] = [];
                    }
                }
            }
        }

        $getNewsImageList = XportalNewsImage::find()->from(XportalNewsImage::tableName())->select(['id', 'news_image_author', 'newsl_image_caption', 'news_image_name', 'news_image_url', 'created_id', 'created_at'])->where(['news_id' => $data['id'], 'news_image_visible' => 1, 'is_del' => 0])->orderBy('position desc')->asArray()->all();

        if ($getNewsImageList) {
            foreach ($getNewsImageList as $key => $value) {
                $data['image'][$key]['id']                  = $value['id'];
                $data['image'][$key]['news_image_author']   = $value['news_image_author'];
                $data['image'][$key]['newsl_image_caption'] = $value['newsl_image_caption'];
                $data['image'][$key]['news_image_name']     = $value['news_image_name'];
                $data['image'][$key]['news_image_url']      = getVar('FILEURL') . $value['news_image_url'];
                $data['image'][$key]['created_id']          = $value['created_id'];
                $data['image'][$key]['created_at']          = $value['created_at'];
            }
        } else {
            $data['image'] = [];
        }

        $parameter = Yii::$app->request->get('parameter', 'project'); //附属标识

        $getChannelOne = XportalChannel::find()->from('xportal_channel')->select(['channel_id'])->Where(['is_del' => 0, 'parameter' => $parameter])->asArray()->one();

        //如果和专题匹配上打上标识
        $categoryBindData = XportalCategoryBind::find()->from('xportal_category_bind')->select('category_id')->where(['is_del' => 0, 'parentid' => $getChannelOne['channel_id']])->column();

        $data['project_mark'] = 0;
        if (in_array($data['catid'], $categoryBindData)) {
            $data['project_mark'] = 1;
        }
        return $data;
    }

    /**
     * 获取视频详情
     * @param $id int 文章id
     * @return array
     */
    static public function getVideoDetail($id)
    {
        $data = XportalNews::find()->from(XportalNews::tableName())
            ->select(['id', 'catid', 'channelid', 'subject_order', 'subject_id', 'title_eyebrow', 'title', 'title_sub', 'content', 'author', 'keywords', 'listorder', 'movie_blankurl', 'relation', 'allow_comment', 'copyfrom', 'islink', 'yes_no_islink', 'click_number', 'index_listorder', 'channel_listorder', 'is_image', 'cache', 'shuffling', 'thumbnail', 'qr_code', 'shuffling_channel', 'news_uploadfile', 'news_type_id', 'news_uploadfile_describe', 'news_movie_uir', 'news_movie_uir_describe', 'top_doing', 'updated_at', 'created_at'])
            ->Where(['id' => $id, 'is_del' => 0, 'status' => getVar('PORTALNEWS'), 'news_type_id' => 3])->asArray()->one();
        return $data;
    }

    /**
     * 获取视频详情数据
     * @param $data
     */
    static public function getVideoDetailData($data)
    {
        //点击量
        $model               = XportalNews::findOne($data['id']);
        $model->click_number = $data['click_number'] + 1;
        $model->save();

        //给图片加上全局域名
        if (!empty($data['shuffling'])) {
            $data['shuffling'] = getVar('FILEURL') . $data['shuffling'];//封面图
        }

        if (!empty($data['thumbnail'])) {
            $data['thumbnail'] = getVar('FILEURL') . $data['thumbnail'];//缩略图
        }

        if (!empty($data['qr_code'])) {
            $data['qr_code'] = getVar('FILEURL') . $data['qr_code'];//二维码
        }

        //替换内容里图片链接
        if (!empty($data['content'])) {
            $data['content'] = self::urlIsStr($data['content']);//替换内容里图片链接
        }

        //判断是否启用外部链接
        if ($data['news_movie_uir'] == 0 && !empty($data['news_movie_uir'])) {
            $data['news_movie_uir'] = json_decode($data['news_movie_uir'], true);
            foreach ($data['news_movie_uir'] as $k => $v) {
                if ($v['status'] == 1) {
                    $data['news_movie_uir'][$k]['file'] = getVar('FILEURL') . $v['file'];
                    if (!empty($v['transcoding'])) {
                        foreach ($v['transcoding'] as $ke => $ve) {
                            $data['news_movie_uir'][$k]['transcoding'][$ke]['url'] = getVar('FILEURL') . $ve['url'];
                        }
                    } else {
                        $data['news_movie_uir'][$k]['transcoding'] = [];
                    }
                }

            }
            $data['movie'] = $data['news_movie_uir'];
            unset($data['news_movie_uir']);
        }

        return $data;
    }

    /**
     * 获取音频详情
     * @param $id int 文章id
     * @return array
     */
    static public function getAudioDetails($id)
    {
        $data = XportalNews::find()->from(XportalNews::tableName())
            ->select(['id', 'catid', 'channelid', 'subject_order', 'subject_id', 'title_eyebrow', 'title', 'title_sub', 'content', 'author', 'keywords', 'listorder', 'movie_blankurl', 'relation', 'allow_comment', 'copyfrom', 'islink', 'yes_no_islink', 'click_number', 'index_listorder', 'channel_listorder', 'is_image', 'cache', 'shuffling', 'thumbnail', 'qr_code', 'shuffling_channel', 'news_uploadfile', 'news_type_id', 'news_uploadfile_describe', 'news_movie_uir', 'news_movie_uir_describe', 'top_doing', 'updated_at', 'created_at'])
            ->Where(['id' => $id, 'is_del' => 0, 'status' => getVar('PORTALNEWS'), 'news_type_id' => 6])->asArray()->one();

        return $data;
    }

    /**
     * 获取音频详情数据
     * @param $data
     */
    static public function getAudioDetailData($data)
    {
        //点击量
        $model               = XportalNews::findOne($data['id']);
        $model->click_number = $data['click_number'] + 1;
        $model->save();

        //给图片加上全局域名
        if (!empty($data['thumbnail'])) {
            $data['thumbnail'] = getVar('FILEURL') . $data['thumbnail'];//缩略图
        }

        if (!empty($data['shuffling'])) {
            $data['shuffling'] = getVar('FILEURL') . $data['shuffling'];//封面图
        }

        if (!empty($data['qr_code'])) {
            $data['qr_code'] = getVar('FILEURL') . $data['qr_code'];//二维码
        }

        //替换内容里图片链接
        if (!empty($data['content'])) {
            $data['content'] = self::urlIsStr($data['content']);//替换内容里图片链接
        }

        //判断是否启用外部链接
        if ($data['news_movie_uir'] == 0 && !empty($data['news_movie_uir'])) {
            $audio = json_decode($data['news_movie_uir'], true);
            foreach ($audio as $k => $v) {
                if ($v['status'] == 1) {
                    $data['audio'][$k]['file']     = getVar('FILEURL') . $v['file'];
                    $data['audio'][$k]['describe'] = $v['describe'];
                    $data['audio'][$k]['fileSize'] = $v['fileSize'];
                    $data['audio'][$k]['fileName'] = $v['fileName'];
                }
            }
            unset($data['news_movie_uir']);
        }

        return $data;
    }

    /**
     * 获取专题列表
     * @param $special
     * @return string
     */
    static public function getSpecialList($special)
    {
        $specialId = $special['id'];
        $newsWhere = new Expression("FIND_IN_SET(:subject_id{$specialId}, subject_id)", [":subject_id{$specialId}" => $specialId]);

        $getNewsList = XportalNews::find()->from(XportalNews::tableName())->select(['id', 'subject_id', 'catid', 'shuffling_index', 'channelid', 'title', 'created_at', 'thumbnail', 'news_type_id', 'shuffling'])->where($newsWhere)->andWhere(['is_del' => 0, 'status' => getVar('PORTALNEWS')]);

        return $getNewsList;
    }


    /**
     * 获取专题列表数据
     * @param $data
     * @return mixed
     */
    static public function getSpecialListData($data)
    {
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
                $getNewsImageCount            = XportalNewsImage::find()->from(XportalNewsImage::tableName())->where(['news_id' => $v['id'], 'news_image_visible' => 1, 'is_del' => 0])->count();
                $data[$k]['news_image_count'] = $getNewsImageCount;
            }

            //判断是否是专题
            $data[$k]['project_mark'] = $v['subject_id'] ? 1 : 0;
            //1大图 0小图
            $data[$k]['shuffling_index'] = $v['shuffling_index'] == 0 ? 0 : 1;
        }

        return $data;
    }


    /**
     * 直播相关推荐
     * @param $news object 关键字
     * @param $id int 文章id
     * @param $num int 条数
     * @return array|\yii\db\ActiveRecord[]
     */
    static public function getRelatedLiveList($news, $id, $num)
    {
        $time = time();
        $s    = ['n.id', 'n.channelid', 'n.catid', 'n.title', 'n.news_type_id', 'n.thumbnail', 'n.created_at', 'n.news_movie_uir'];
        //按关键字匹配数据
        $dataList = [];
        if (!empty($news->keywords)) {
            $keywords = explode(',', $news->keywords);//分割字符串
            //模糊匹配关键字
            $dataList = XportalNews::find()->from(XportalNews::tableName() . ' n')->select($s)->leftJoin('xportal_news_live as l', 'n.id = l.news_id')->where(['or like', 'n.title', $keywords])->andWhere(['n.catid' => $news->catid, 'n.is_del' => 0])->andWhere(['not in', 'n.id', $id])->andWhere(['n.status' => getVar('PORTALNEWS')])->andWhere(['<', 'l.start_time', $time])->andWhere(['>', 'l.end_time', $time])->limit('10')->asArray()->all();
        }
        //按栏目匹配数据
        if (count($dataList) < $num) {
            //返回数组中id的值
            $arrayColum = array_column($dataList, 'id');
            $arrayColum = array_merge($arrayColum, [$id]);
            //计算要获取的数量
            $remainingNum = $num - count($dataList);

            $dataCatidList = XportalNews::find()->from(XportalNews::tableName() . ' n')->select($s)->leftJoin('xportal_news_live as l', 'n.id = l.news_id')->where(['n.catid' => $news->catid, 'n.is_del' => 0])->andWhere(['not in', 'n.id', $arrayColum])->andWhere(['n.status' => getVar('PORTALNEWS')])->andWhere(['<', 'l.start_time', $time])->andWhere(['>', 'l.end_time', $time])->limit($remainingNum)->orderBy('n.listorder desc')->asArray()->all();
            $dataList      = array_merge($dataList, $dataCatidList);
        }
        //按频道匹配数据
        if (count($dataList) < $num) {
            //返回数组中id的值
            $arrayColum = array_column($dataList, 'id');
            $arrayColum = array_merge($arrayColum, [$id]);
            //计算要获取的数量
            $remainingNum = $num - count($dataList);

            $dataChannelList = XportalNews::find()->from(XportalNews::tableName() . ' n')->select($s)->leftJoin('xportal_news_live as l', 'n.id = l.news_id')->where(['n.channelid' => $news->channelid, 'n.is_del' => 0])->andWhere(['not in', 'n.id', $arrayColum])->andWhere(['n.status' => getVar('PORTALNEWS')])->andWhere(['<', 'l.start_time', $time])->andWhere(['>', 'l.end_time', $time])->limit($remainingNum)->orderBy('n.listorder desc')->asArray()->all();
            $dataList        = array_merge($dataList, $dataChannelList);
        }

        return $dataList;
    }

    /**
     * 相关推荐
     * @param $news object 关键字
     * @param $id int 文章id
     * @param $num int 条数
     * @return array|\yii\db\ActiveRecord[]
     */
    static public function getRelatedList($news, $id, $num)
    {
        $select   = ['id', 'channelid', 'catid', 'title', 'news_type_id', 'thumbnail', 'created_at', 'news_movie_uir'];
        $dataList = [];
        //按关键字匹配数据
        if (!empty($news->keywords)) {
            $keywords = explode(',', $news->keywords);//分割字符串
            //模糊匹配关键字
            $dataList = XportalNews::find()->select($select)->where(['or like', 'title', $keywords])->andWhere(['catid' => $news->catid, 'is_del' => 0])->andWhere(['not in', 'id', $id])->andWhere(['status' => getVar('PORTALNEWS')])->limit('10')->asArray()->all();
        }
        //按栏目匹配数据
        if (count($dataList) < $num) {
            //返回数组中id的值
            $arrayColum = array_column($dataList, 'id');
            $arrayColum = array_merge($arrayColum, [$id]);
            //计算要获取的数量
            $remainingNum = $num - count($dataList);

            $dataCatidList = XportalNews::find()->select($select)->where(['catid' => $news->catid, 'is_del' => 0])->andWhere(['not in', 'id', $arrayColum])->andWhere(['status' => getVar('PORTALNEWS')])->limit($remainingNum)->orderBy('listorder desc')->asArray()->all();
            $dataList      = array_merge($dataList, $dataCatidList);
        }
        //按频道匹配数据
        if (count($dataList) < $num) {
            //返回数组中id的值
            $arrayColum = array_column($dataList, 'id');
            $arrayColum = array_merge($arrayColum, [$id]);
            //计算要获取的数量
            $remainingNum = $num - count($dataList);

            $dataChannelList = XportalNews::find()->select($select)->where(['channelid' => $news->channelid, 'is_del' => 0])->andWhere(['not in', 'id', $arrayColum])->andWhere(['status' => getVar('PORTALNEWS')])->limit($remainingNum)->orderBy('listorder desc')->asArray()->all();
            $dataList        = array_merge($dataList, $dataChannelList);
        }

        return $dataList;
    }


    /**
     * 获取相关推荐列表数据
     * @param $dataList
     * @return mixed
     */
    static public function getRelatedListData($dataList)
    {
        foreach ($dataList as $k => $v) {
            //统计视频时长
            $dataList[$k]['news_movie_uir'] = null;
            if (!empty($v['thumbnail'])) {
                $dataList[$k]['thumbnail'] = getVar('FILEURL') . $v['thumbnail'];
            }
            if (!empty($v['shuffling'])) {
                $dataList[$k]['shuffling'] = getVar('FILEURL') . $v['shuffling'];
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
                        $dataList[$k]['news_movie_uir'][$key]['file'] = getVar('FILEURL') . $value['file'];
                    }
                }
            }

            //查询图集个数
            $dataList[$k]['news_image_count'] = 0;
            if ($v['news_type_id'] == 2) {
                $getNewsImageCount                = XportalNewsImage::find()->from(XportalNewsImage::tableName())->where(['news_id' => $v['id'], 'news_image_visible' => 1, 'is_del' => 0])->count();
                $dataList[$k]['news_image_count'] = $getNewsImageCount;
            }
        }

        return $dataList;
    }


    /**
     * @param $str
     * @param string $find
     * @param string $substr
     * @return mixed
     */
    static public function urlIsStr($str, $find = 'src="/', $substr = '/')
    {
        $domain = getVar('FILEURL');
        $count  = strpos($str, $find);
        if (!$count) {
            return $str;
        }
        $strlen = strlen($find);
        $strat  = explode($substr, $find);
        $strs   = substr_replace($str, $strat[0] . $domain . $strat[1], $count, $strlen);

        //查看还有没有
        return self::urlIsStr($strs, $find, $substr);
    }


    /**
     * 判断文章是否存在
     * @param $id int 文章id
     * @return bool
     */
    static public function isNews($id)
    {
        if (XportalNews::findOne($id)) {
            return true;
        }
        return false;
    }


}