<?php

namespace common\utils;

use Yii;

class LogUtil
{
    /**
     * 设置记录日志
     * @param $message 需要记录的名称
     * @param $data  需要记录的参数信息
     * @param $fileName 文件夹名称
     * @author weihuaadmin@163.com
     */
    public static function SetLog($message, $data, $filderName = '')
    {
        try {
            $filderName = empty($filderName) ? Yii::$app->controller->id . '@' . Yii::$app->controller->action->id : $filderName;
            $dir = \Yii::$app->basePath . "/runtime/logs/" . $filderName . '/';
            $fileName = $filderName . date('Y-m-d') . '.log';
            $data = "\r\n记录时间：" . date('Y-m-d H:i:s') . "\r\n记录名称：" . $message . "\r\n记录参数：" . print_r($data, true);
            if (!is_dir($dir)) {
                if (!@mkdir($dir)) throw  new \Exception('Create folder failed ');
                //修改权限
                @chmod($dir, 0777);
            }
            $fg = @fopen($dir . $fileName, 'a');
            if (!$fg) throw  new \Exception('File open failed');
            @fwrite($fg, $data);
            @fclose($fg);
        } catch (Exception $ex) {
            \Yii::error('日志写入异常');
        }
    }

    /**
     * @Function: 设置异常日志
     * @Author: harxingxing@163.com
     * @Date: 2019/4/23 9:56
     * @param $title
     * @param $e
     * @throws \Exception
     */
    public static function setExceptionLog($title, $e)
    {
        self::SetLog($title, $e->getFile() . ':' . $e->getLine() . ' ' . $e->getMessage(), '');
    }
}

	