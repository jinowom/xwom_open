<?php

namespace services\api;

use Yii;
use common\components\Service;
use backend\modules\xportal\models\XportalCollectionSite;
use backend\modules\xportal\models\XportalChannel;
use backend\modules\xportal\models\XportalCategoryBind;
use backend\modules\xportal\models\XportalCategory;
use services\ResponseService;

/**
 * Class ApiService
 * @package services\api
 * author:rjl
 */
class ApiService extends Service
{
    /**
     * @return int
     * @throws \yii\db\Exception
     */
    public function getDefaultDbSize()
    {
        $db = Yii::$app->db;
        $models = $db->createCommand('SHOW TABLE STATUS')->queryAll();
        $models = array_map('array_change_key_case', $models);
        // 数据库大小
        $mysqlSize = 0;
        foreach ($models as $model) {
            $mysqlSize += $model['data_length'];
        }

        return $mysqlSize;
    }


    /**
     * 查询访问是否合法
     * @param $sign string 秘钥
     * @throws \yii\web\UnauthorizedHttpException
     * @author rjl
     */
    public function getIsSgin($sign)
    {
        try {
            if (empty($sign)){
                throw new \Exception("秘钥为空！");
            }
            //查询当前秘钥key是否存在
            $siteOne = XportalCollectionSite::find()->where(['add_name'=>$sign])->one();
            if (empty($siteOne)){
                throw new \Exception("秘钥未找到！");
            }
            return $siteOne;
        } catch (\Exception $e) {
//            echo $e->getMessage();exit;
            throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限','403');
        }
    }

    /**
     * 查找频道列表列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getChannelList()
    {
        $list = XportalChannel::find()->from('xportal_channel')
            ->select(['channel_id', 'parent_id', 'channel_ch_name', 'channel_en_name', 'channel_alias', 'channel_listorder', 'channel_theme_id', 'channel_description', 'bank_url', 'parameter', 'cache', 'surface_plot', 'pic', 'app_sort', 'channel_top', 'siteid', 'created_at', 'updated_at'])
            ->Where(['is_del' => 0, 'ismenu' => 1])
            ->orderBy("created_at DESC")
            ->orderBy("channel_listorder DESC")->asArray()->all();

        return $list;
    }

    /**
     * 根据频道id获取栏目列表
     * @param $channelId int 频道id
     * @return array|\yii\db\ActiveRecord[]
     * @author rjl
     */
    public static function getCategoryList($channelId)
    {
        if (empty($channelId)){
            return [];
        }

        $categoryBindData = XportalCategoryBind::find()->from('xportal_category_bind')->select('category_id')->where(['is_del'=>0,'parentid'=>$channelId])->column();

        if (empty($categoryBindData)){
            return [];
        }

        $data = XportalCategory::find()->from('xportal_category')->select(['catid', 'catname', 'letter', 'alias', 'module', 'category_theme', 'temparticle', 'listorder', 'description', 'bank_url', 'parentdir', 'catdir', 'url', 'is_link', 'hits', 'parameter', 'surface_plot', 'pic', 'siteid', 'cache', 'updated_at', 'created_at'])->where(['in', 'catid', $categoryBindData])->andWhere(['ismenu' => 1])->all();
        if (empty($data)) {
            return [];
        }

        return $data;
    }

}