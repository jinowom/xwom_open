<?php


namespace console\lib;


use console\models\TaskLogModel;
use console\models\TaskModel;
use Yii;
use yii\console\Exception;
use yii\web\Controller;

class WebBaseController extends Controller
{
    public $task;
    public $log;

    public function beforeAction($action)
    {
        try{
            if (parent::beforeAction($action)){
                $url = Yii::$app->request->absoluteUrl;
                $task = TaskModel::find(['like','program',$url])->one();
                if(!$task){
                    throw new Exception("Can not find ".$url." in console tasks. Please check it.");
                }
                $this->task = $task;
                //执行任务前的初始化
                $this->taskInit();
                //记录执行日志
                $this->log = new TaskLogModel();
                $this->log->task_id = $this->task->id;
                $this->log->start_time = date("Y-m-d H:i:s");
                $this->log("Task [".$task->program."] started.");
            }
        }catch (\Exception $e){
            WriteLogTool::writeLog($e->getMessage(),$url);exit;
        }
        return true;
    }

    public function afterAction($action, $result)
    {
        if (parent::afterAction($action, $result)){
            $this->task->last_finish_time = date("Y-m-d H:i:s",time());
            if(!$result){
                //改状态
                $this->task->status=TaskModel::RUNNING_FAILED;

                $this->task->is_kill = TaskModel::TASK_IS_KILLED;
            }else{
                $this->task->status=TaskModel::RUNNING_SUCCESS;
            }
            //任务执行时间
            $this->task->run_time = strtotime($this->task->last_finish_time) - strtotime($this->task->last_start_time);
            $this->task->save(false);
            $this->log->finish_time = $this->task->last_finish_time;
            $this->log("Task [".$this->task->program."] finished.");
            $this->log->save(false);
        }
        return true;
    }

    public function log($str)
    {
        $this->log->info.="\n ".date('Y-m-d H:i:s')."---->".$str;
    }

    /**
     * @param $task
     * 执行任务前的初始化操作
     */
    public function taskInit(){
        try{
            $this->task->status = TaskModel::RUNNING;
            $this->task->last_start_time = date('Y-m-d H:i:s',time());
            if ($this->task->type == TaskModel::RUN_INTERVAL){
                $this->task->next_start_time = date('Y-m-d H:i:s',(strtotime($this->task->last_start_time) + $this->task->info));
            }
            if ($this->task->type == TaskModel::RUN_FIXED_TIME){
                $nowDay = date('Y-m-d',time());
                $nextDay = date('Y-m-d',strtotime("$nowDay +1 day"));
                $this->task->next_start_time = $nextDay .' '. $this->task->info;
            }
            $this->task -> save(false);
        }catch (\Exception $e){
            WriteLogTool::writeLog($e->getMessage());exit;
        }
        return true;
    }
}