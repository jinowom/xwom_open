<?php

namespace console\models;

use console\lib\ProcessTool;
use console\lib\WriteLogTool;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "t_task".
 *
 * @property int $id
 * @property string $created_at 创建时间
 * @property string $created_by 创建人
 * @property string $updated_at 修改时间
 * @property string $updated_by 修改人
 * @property string $name 任务名称
 * @property string $program 执行程序
 * @property string $pid pid
 * @property string $timeOut 执行超时时间（单位秒）
 * @property string $type 任务类型（1一次执行2间隔执行3指定时间执行单次执行4指定时间永久执行）
 * @property string $start_time 任务开始时间
 * @property string $info 任务信息
 * @property string $status 任务状态(0未开始1正在执行2执行成功3执行失败)
 * @property string $last_start_time 上次开始执行时间
 * @property string $last_finish_time 上次结束执行时间
 * @property string $next_start_time 下次开始执行时间
 * @property string $is_kill 进程是否杀死（0否1是）
 * @property string $run_time 任务运行时间
 */
class TaskModel extends ActiveRecord
{
    /**
     *任务类型
     */
    const RUN_ONCE = 1; //一次执行

    const RUN_INTERVAL = 2; //间隔执行

    const RUN_FIXED_TIME = 3; //指定时间永久运行


    /**
     *任务状态
     *
     */
    const NOT_RUNNING = 0; //未运行

    const RUNNING = 1; //正在运行

    const RUNNING_SUCCESS = 2; //运行成功

    const RUNNING_FAILED = 3; //运行失败

    const TASK_IS_KILLED = 1; //进程被杀死

    const TASK_NOT_START = 0; //任务还未执行

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'program', 'timeOut', 'type', 'status'], 'required'],
            [['created_at', 'updated_at', 'type', 'start_time', 'status', 'last_start_time', 'last_finish_time', 'next_start_time'], 'string', 'max' => 50],
            [['created_by', 'updated_by'], 'string', 'max' => 100],
            [['name', 'program', 'info'], 'string', 'max' => 500],
            [['pid', 'timeOut'], 'string', 'max' => 20],
            [['is_kill'], 'string', 'max' => 2],
            [['run_time'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => '创建时间',
            'created_by' => '创建人',
            'updated_at' => '修改时间',
            'updated_by' => '修改人',
            'name' => '任务名称',
            'program' => '执行程序',
            'pid' => 'Pid',
            'timeOut' => '执行超时时间（单位秒）',
            'type' => '任务类型',
            'start_time' => '任务开始时间',
            'info' => '任务信息',
            'status' => '任务状态',
            'last_start_time' => '上次开始执行时间',
            'last_finish_time' => '上次结束执行时间',
            'next_start_time' => '下次开始执行时间',
            'is_kill' => '进程是否杀死',
            'run_time' => '任务运行时间',
        ];
    }

    /**
     *超时任务定义：正在执行，现在的时间 > 上次开始执行的时间 + 执行超时时间
     */
    public function checkTimeOutTask(){
        try{
            $timeOutTasks = array();
            $now = time();
            $list = self::find()->where(['status' => self::RUNNING]) -> all();
            foreach ($list as $item){
                $endTime = strtotime($item->last_start_time) + $item->timeOut;
                if ($now > $endTime){
                    $timeOutTasks[] = $item;
                }
            }
            if ($timeOutTasks){
                $processTool = new ProcessTool();
                foreach ($timeOutTasks as $timeOutTask){
                    //先检查是否有进程
                    if ($processTool->checkProcessIsRunByName($timeOutTask->program)){
                        //有就杀掉，并改任务信息，写入日志
                        if ($processTool->killProcessByName($timeOutTask->program)){
                            WriteLogTool::writeLog('执行超时，已被杀掉',$timeOutTask->program);
                            $timeOutTask->status = TaskModel::RUNNING_FAILED;
                            $timeOutTask->is_kill = TaskModel::TASK_IS_KILLED;
                            $timeOutTasks->pid = null;
                            $timeOutTask->save(false);
                        }
                    }else{
                        throw new Exception("<$timeOutTask->program>已经被自动清除，无需执行kill");
                    }
                }
            }
        }catch (\Exception $e){
            WriteLogTool::writeLog($e->getMessage());
        }
        return true;
    }

    /**
     * 检查需要执行的进程
     *排除执行失败、被杀死的、正在运行的
     */
    public function checkNeedRunTask(){
        try{
            $needRunTasks = self::find()->where(['!=','status',self::RUNNING_FAILED])->andWhere(['!=','is_kill',self::TASK_IS_KILLED])->andWhere(['!=','status',self::RUNNING])->all();
            if ($needRunTasks){
                foreach ($needRunTasks as $task) {
                    $now = date('Y-m-d H:i:s',time());
                    switch($task->type){
                        /**
                         *一次执行，指定时间执行
                         */
                        case TaskModel::RUN_ONCE:
                            if($now >= $task->start_time && $task->status==TaskModel::TASK_NOT_START){
                               $this->execTask($task);
                            }
                            break;
                        /**
                         *间隔执行
                         */
                        case TaskModel::RUN_INTERVAL:
                            if($now >= $task->start_time){
                                //第一次执行
                                if(empty($task->last_start_time)){
                                    $this->execTask($task);
                                }else{
                                    //间隔执行
                                    if(strtotime($now) - strtotime($task->last_start_time) >= intval($task->info)){
                                        $this->execTask($task);
                                    }
                                }
                            }
                            break;
                        /**
                         *固定时间永久执行(定时执行)
                         */
                        case TaskModel::RUN_FIXED_TIME:
                            //如果没有执行过,并且大于等于该执行的时间则执行
                            if ($task->status == 0 && $now >= $task->start_time){
                                $currentTime = date('H:i:s');
                                if($currentTime > $task->info){
                                    $this->execTask($task);
                                }

                            }
                            //如果今天没有执行过，就执行。
                            if($now >= $task->start_time && date("Y-m-d",strtotime($task->last_start_time)) != date("Y-m-d",time()) && $task->status == TaskModel::RUNNING_SUCCESS && $task->is_kill != TaskModel::TASK_IS_KILLED){
                                //每天超过指定时间便执行
                                $currentTime = date('H:i:s');
                                if($currentTime > $task->info){
                                    $this->execTask($task);
                                }
                            }
                            break;
                    }
                }
            }
        }catch (\Exception $e){
            WriteLogTool::writeLog($e->getMessage());
        }
        return true;
    }

    /**
     * @param $task
     * @param null $commond
     * @return bool
     * 执行任务
     */
    public function execTask($task){
        try{
            if (preg_match('/^http/i',$task->program)){
                $commond = "wget $task->program";
            }else{
                $commond = "php /data/websites/yii/test/yii  $task->program";
            }
            $runOnceProcess = new ProcessTool($commond);
            $pid = $runOnceProcess->getPid();
            if ($pid){
                $task->pid = $pid;
                $task->save(false);
            }else{
                throw new Exception("<$task->program>执行失败！");
            }
        }catch (\Exception $e){
            WriteLogTool::writeLog($e->getMessage());
        }
        return true;
    }



}