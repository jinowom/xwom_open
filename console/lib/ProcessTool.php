<?php

namespace console\lib;

class ProcessTool
{
    /**
     * @var
     * 运行时的PID
     */
    private $pid;
    /**
     * @var bool
     * 运行的命令
     */
    private $command;

    /**
     * ProcessTool constructor.
     * @param bool $cl
     * 构造函数初始化
     */
    public function __construct($cl=false){
        if ($cl != false){
            $this->command = $cl;
            $this->runCommand();
        }
    }

    /**
     *后台运行命令，返回pid,并给pid属性赋值
     */
    private function runCommand(){
        $command = 'nohup '.$this->command.' > /dev/null 2>&1 & echo $!';
        exec($command ,$op);
        $this->pid = (int)$op[0];
    }

    /**
     * @param $pid
     * 设置PID
     */
    public function setPid($pid){
        $this->pid = $pid;
    }

    /**
     * @return mixed
     * 获取pid
     */
    public function getPid(){
        return $this->pid;
    }

    /**
     * @return bool
     * 获取命令执行的状态
     */
    public function status(){
        $command = 'ps -p '.$this->pid;
        exec($command,$op);
        if (!isset($op[1]))return false;
        else return true;
    }

    /**
     * @return bool
     * 开始运行命令
     */
    public function start(){
        if ($this->command != ''){
            $this->runCommand();
        }
        return true;
    }

    /**
     * @return bool
     * 杀掉进程
     */
    public function stop(){
        $command = 'kill '.$this->pid;
        exec($command);
        if ($this->status() == false)return true;
        else return false;
    }

    /**
     *检查进程是否在运行，通过pid
     */
    public function checkProcessIsRunByPid($pid){
        $this->setPid($pid);
        return $this->status();
    }

    /**
     * 通过进程名称检查进程是否运行
     */

    public function checkProcessIsRunByName($program){
        $cmd="ps -ef |grep -v grep|grep -v 'sh -c' | grep '$program' |awk '{print $2}'";

        exec($cmd,$output,$return_var);

        if (count($output)>0){
            $this->pid = (int)$output[0];
            return true;
        }
        return false;
    }

    /**
     *通过pid杀进程
     */
    public function killProcessByPid($pid){
        if ($this->checkProcessIsRunByPid($pid)){
            $cmd = "sudo kill -9  $pid";
            exec($cmd);
        }else{
            return false;
        }
        return !$this->checkProcessIsRunByPid($pid);
    }

    /**
     * 通过进程名称杀进程
     */
    public function killProcessByName($program){
        if ($this->checkProcessIsRunByName($program)){
            $cmd="sudo kill -9 $this->pid";
            exec($cmd);
        }else{
            return false;
        }
        return !$this->checkProcessIsRunByName($program);
    }

    /**
     *检查主进程是否启动，因为在crontab里面执行了main/index，所以通过checkProcessIsRunByName，是不能判断的，每次都会返回true
     */
    public static function checkMainIsRun(){
        $cmd = "ps -ef |grep -v grep|grep -v 'sh -c' | grep main/index";

        exec($cmd,$op);

        return count($op)>1;
    }
}