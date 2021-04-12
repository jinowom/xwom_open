<?php

namespace common\helpers;

/**
 * 生成二维码助手类
 */
class PhpQRCode{
    public static function getQrcode($data="",$code_dir,$fileName){
        $code = new \QRcode();
        $codeFileUrl = $code_dir . $fileName;
        if (file_exists($codeFileUrl)) {
            return $codeFileUrl;
        }
        if (!file_exists($code_dir)) {
            mkdir($code_dir);
            chmod($code_dir, 0755);
        }
        //生成二维码文件
        $code::png($data, $codeFileUrl, 'L', '2', 2);
        return $codeFileUrl;
    }
}