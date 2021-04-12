<?php
/**
 * Created by PhpStorm.
 * User: weihuaadmin
 * Date: 2020/04/27
 */

namespace backend\behaviors;

use common\utils\ToolUtil;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;
use common\traits\BaseTraits;
use backend\modules\xedit\models\ReviewLog;
use yii\helpers\Json;

class ReviewLogBehavior extends Behavior
{
    //继承公共方法
    use BaseTraits;

    public function events(){
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
        ];
    }

    public function afterSave(AfterSaveEvent $afterSaveEvent){
        if(isset($afterSaveEvent->changedAttributes['status'])){
            $reviewLog = new ReviewLog();
            $reviewLog->setAttributes([
                'news_id' => $afterSaveEvent->sender->id,
                'user_id' => self::GetUserId(),
                'status' => $afterSaveEvent->changedAttributes['status'],
                'changed_status' => $afterSaveEvent->sender->status,
                'content' => ToolUtil::getIsset($afterSaveEvent->sender->remark,'')
            ]);
            if(!$reviewLog->save()){
                exit(Json::encode(['status' => false, 'msg' => '审核失败']));
            }
        }
    }
}