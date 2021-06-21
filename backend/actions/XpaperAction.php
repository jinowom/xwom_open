<?php
/**
 * Created by PhpStorm.
 * User: WANGWEIHUA
 * Date: 2020/4/22
 * Time: 19:47
 */
namespace backend\actions;
use backend\modules\xedit\models\XedPaper;
use backend\modules\xedit\models\XedPaperPage;
use common\utils\ToolUtil;
use yii\base\Action;

class XpaperAction extends Action
{
    //这里面的三个参数的值是通过控制器actions中配置而来的
    public $pId; //报纸ID

    /**
     * @Function 获取版面信息
     * @Author Weihuaadmin@163.com
     * @return array
     */
    public function run(){
        $return = [];
        $pages = XedPaperPage::findAllByWhere(['paper_id' => $this->pId],['paper_issue','id','page','page_name'],[]);
        foreach ($pages as $key => $page) {
            $page_name = $page['page_name'];
            $return[$page['paper_issue'].'-'.$page['id']] = $page['paper_issue'].'期第'.$page['page']."版($page_name)";
        }
        return ToolUtil::returnAjaxMsg(true,$return);
    }
}