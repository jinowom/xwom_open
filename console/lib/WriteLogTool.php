<?php


namespace console\lib;

/**
 * Class WriteLogTool
 * @package console\lib
 * 记录日志工具
 * 日志报错在console\runtime\ 目录下，以每天的日期命名
 * 默认的日志文件名为taskLog.txt
 *
 */
class WriteLogTool
{
    /**
     *写日志工具
     */
    public static function writeLog($data = 'No incoming data,Please check it!!!',$unqid = '',$filename = 'taskLog.txt',$type = FILE_APPEND){

        $path = \Yii::getAlias('@console') . '/runtime/' .date('Ymd');
        $file = \Yii::getAlias('@console') . '/runtime/' .date('Ymd') . '/'.$filename;
        if (!file_exists($path)){
            mkdir($path,0777,true);
        }

        file_put_contents($file,static::logFormat($data,$unqid),$type);

    }

    /**
     *日志格式
     */
    public static function logFormat($data,$unqid = ''){
        $header = '>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>'.PHP_EOL;
        $content = date('Y-m-d H:i:s').PHP_EOL;
        $data = "<$unqid>" . $data . PHP_EOL;
        $footer = '----------------------------------------------------------------------------------------------------------'.PHP_EOL;

        return $header . $content . $data . $footer;
    }

    /**
     *输出日志
     * timeDay 格式为Ymd
     */
    public static function outputLog($filename = 'taskLog.txt',$timeDay = ''){
        $day = empty($timeDay) ? date('Ymd') : $timeDay;

        $path = \Yii::getAlias('@console') . '/runtime/' .$day . '/'.$filename;

        $content = file_exists($path) ? file_get_contents($path) : '暂无运行日志';

        return $content;
    }
}