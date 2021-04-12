<?php
namespace console\controllers;

use console\lib\ProcessTool;
use console\models\TaskModel;
use yii\console\Controller;


class MainController extends Controller
{

    private $timeInterval = '10'; //任务执行时间间隔

    /**
     * 主进程，每三分钟扫描一次，有任务就执行，没有就退出
     * 任务开始的时间：是数字则表示间隔执行，是H:i:s则表示指定时间永久执行，单次执行或者指定时间单次执行
     *
     * 使用yii控制台命令，program如 test/index，不使用则wget请求，program要填写完整的名称，如http://huangdingbo.work/test/backend/web/index.php?r=task%2Findex
     *
     * 使用yii控制台命令，创建要执行的任务代码，必须集成ConsoleBaseController,使用wget创建要执行的任务代码，必须继承WebBaseController
     */
    public function actionIndex(){
        //如果Main进程已经在运行了，则不启动，保持主进程只有一个
        if(ProcessTool::checkMainIsRun())
        {
            echo '主进程已启动';exit();
        }
        //后台任务模型
        $model = new TaskModel();
        //每N秒检查一次任务列表
        while (1) {
            // 检查是否有超时的任务并杀掉
            $model->checkTimeOutTask();
            //查找需要执行的任务,并执行
            $model->checkNeedRunTask();
            sleep($this->timeInterval);
        };

    }

}