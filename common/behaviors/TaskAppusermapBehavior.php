<?php
/**
 * Created by PhpStorm.
 * Date: 2018/12/17
 * Time: 9:27
 */

namespace common\behaviors;


use backend\modules\xedit\models\XedToBeDistributedNews;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;
use backend\models\taskpapertag\TaskAppusermap;

class TaskAppusermapBehavior extends Behavior
{
    public function events(){
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * @Function 插入之后的行为
     * @Author Weihuaadmin@163.com
     * @param $event
     */
    public function afterInsert($event)
    {
        if(isset($event->sender->app_id)){
            $getNewsModel = new XedToBeDistributedNews();
            $appInfo = $getNewsModel->findOne(['app_id'=>$event->sender->app_id]);
            if($appInfo){
                $appInfo->updateCounters(['totalamount' => 1]);
            }else{
                $getNewsModel ->setAttributes([
                    'app_id'=>$event->sender->app_id,
                    'totalamount'=>1,
                ]);
                $getNewsModel->save();
            }
        }
    }

    /**
     * @Function 删除之后的行为
     * @Author Weihuaadmin@163.com
     * @param $event
     */
    public function afterDelete($event){
        if(isset($event->sender->app_id)){
            $taskAppusermap = new TaskAppusermap();
            $appInfo = $taskAppusermap->findOne(['app_id'=>$event->sender->app_id]);
            if(!empty($appInfo)){
                $appInfo->updateCounters(['totalamount' => -1]);
            }
        }
    }
}