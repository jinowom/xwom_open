<?php
namespace api\modules\v1\controllers;

use backend\modules\xportal\models\XportalNews;
use backend\modules\xportal\models\XportalNewsImage;
use backend\modules\xportal\models\XportalChannel;
use backend\modules\xportal\models\XportalCategory;
use services\ErrorService;
use services\ResponseService;

/**
 * Site controller
 */
class AtlasManageController extends CommonController
{
    private $portalnews;//已发布标识

    public function init()
    {
        parent::init();
        $this->portalnews = getVar('PORTALNEWS');//已发布标识
    }

    /**
     * 获取图集列表
     * @return array|\yii\web\Response
     */
    public function actionGetAtlasList()
    {
        userLog(3, 2, '获取图集列表');
        $pageSize = $this->getPage();

        $type     = $this->get('type', null);//类型
        $id       = $this->get('id', null);//频道id和栏目id
        $page     = $this->get('page', 1);//页数
        $newsType = $this->get('news_type', 2);//资源库类型
        $offset   = ($page - 1) * $pageSize;
        $sortord  = $this->get('sortord', 'top_doing desc, listorder desc, updated_at desc, created_at'); //排序


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
            $where = ['channelid' => $id, 'is_del' => 0, 'status' => $this->portalnews];
        } elseif ($type == 2 && !empty($id)) {
            //判断栏目是否存在
            if (!XportalCategory::findOne($id)) {
                return ResponseService::apiResponse(ErrorService::CATEGORY_NO_EMPTY, '此数据不存在', (object)[]);
            }
            $where = ['catid' => $id, 'is_del' => 0, 'status' => $this->portalnews];
        }

        //查询条件不能为空
        if (empty($where)) {
            return ResponseService::response(ErrorService::PARAMETER_ERROR);
        }

        if (empty($newsType)){
            return ResponseService::response(ErrorService::NOT_FOUND_REPOSITORY);
        }

        $getNewsList = XportalNews::find()->from(XportalNews::tableName())->select(['id','title'])->where($where)->andWhere(['news_type_id' => $newsType]);

        $this->sidx = $sortord;
        $data = $this->getPageSize($getNewsList,$offset);

        if (empty($data)) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        }

        //查询每个文章的图集
        foreach ($data as $k => $v){
            $getNewsImageList = XportalNewsImage::find()->from(XportalNewsImage::tableName())->select(['id','news_image_author','newsl_image_caption','news_image_name','news_image_url','created_id','created_at'])->where(['news_id' =>$v['id'],'news_image_visible' => 1, 'is_del' => 0])->orderBy('position desc')->asArray()->all();
            
            if ($getNewsImageList){
                foreach ($getNewsImageList as $key => $value){
                    $data[$k]['image'][$key]['id']                  = $value['id'];
                    $data[$k]['image'][$key]['news_image_author']   = $value['news_image_author'];
                    $data[$k]['image'][$key]['newsl_image_caption'] = $value['newsl_image_caption'];
                    $data[$k]['image'][$key]['news_image_name']     = $value['news_image_name'];
                    $data[$k]['image'][$key]['news_image_url']      = getVar('FILEURL').$value['news_image_url'];
                    $data[$k]['image'][$key]['created_id']          = $value['created_id'];
                    $data[$k]['image'][$key]['created_at']          = $value['created_at'];
                }
            } else {
                $data[$k]['image'] = [];
            }
        }

        return ResponseService::response(ErrorService::STATUS_SUCCESS,$data);
    }
}
