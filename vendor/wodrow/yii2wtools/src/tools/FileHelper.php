<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-7-11
 * Time: 下午5:41
 */

namespace wodrow\yii2wtools\tools;


use creocoder\flysystem\Filesystem;

class FileHelper extends \yii\helpers\FileHelper
{
    //$filePath是服务器的文件地址
    //$saveAsFileName是用户指定的下载后的文件名
    public function download1($filePath, $saveAsFileName = null)
    {

        if (!$saveAsFileName) {
            $saveAsFileName = basename($filePath);
        }
        // 清空缓冲区并关闭输出缓冲
        ob_end_clean();

        //r: 以只读方式打开，b: 强制使用二进制模式
        $fileHandle = fopen($filePath, "rb");
        if ($fileHandle === false) {
            echo "Can not find file: $filePath\n";
            exit;
        }

        Header("Content-type: application/octet-stream");
        Header("Content-Transfer-Encoding: binary");
        Header("Accept-Ranges: bytes");
        Header("Content-Length: " . filesize($filePath));
        Header("Content-Disposition: attachment; filename=\"$saveAsFileName\"");

        while (!feof($fileHandle)) {

            //从文件指针 handle 读取最多 length 个字节
            echo fread($fileHandle, 32768);
        }
        fclose($fileHandle);
    }

    public function download2($filepath)
    {
        set_time_limit(0);  //大文件在读取内容未结束时会被超时处理，导致下载文件不全。

        $fpath = $filepath;
        $file_pathinfo = pathinfo($fpath);
        $file_name = $file_pathinfo['basename'];
        $file_extension = $file_pathinfo['extension'];
        $handle = fopen($fpath, "rb");
        if (FALSE === $handle)
            exit("Failed to open the file");
        $filesize = filesize($fpath);

        header("Content-type:application/octet-stream");//更具不同的文件类型设置header输出类型
        header("Accept-Ranges:bytes");
        header("Accept-Length:" . $filesize);
        header("Content-Disposition: attachment; filename=" . $file_name);

        while (!feof($handle)) {
            $contents = fread($handle, 8192);
            echo $contents;
            @ob_flush();  //把数据从PHP的缓冲中释放出来
            flush();      //把被释放出来的数据发送到浏览器
        }
        fclose($handle);
        exit;
    }

    /**
     * 上传base64文件
     * @param $base64
     * @return array|bool
     */
    public static function base64_upload($base64)
    {
        $base64_image = str_replace(' ', '+', $base64);
        //post的数据里面，加号会被替换为空格，需要重新替换回来，如果不是post的数据，则注释掉这一行
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)) {
            //匹配成功
            if ($result[2] == 'jpeg') {
                $image_name = 'save.jpg';
                //纯粹是看jpeg不爽才替换的
            } else {
                $image_name = 'save.' . $result[2];
            }
            $image_file = $image_name;
            //服务器文件存储路径
            if (file_put_contents($image_file, base64_decode(str_replace($result[1], '', $base64_image)))) {
                return ['status' => 200, 'store_path' => $image_file];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function chmodr($path, $filemode)
    {
        if (!is_dir($path))
            return chmod($path, $filemode);
        $dh = opendir($path);
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..') {
                $fullpath = $path . '/' . $file;
                if (is_link($fullpath))
                    return FALSE;
                elseif (!is_dir($fullpath) && !chmod($fullpath, $filemode))
                    return FALSE;
                elseif (!self::chmodr($fullpath, $filemode))
                    return FALSE;
            }
        }
        closedir($dh);
        if (chmod($path, $filemode))
            return TRUE;
        else
            return FALSE;
    }

    public static function html2word($html, $path)
    {
        ob_start();
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
        xmlns:w="urn:schemas-microsoft-com:office:word"
        xmlns="http://www.w3.org/TR/REC-html40">';
        echo $html;
        echo "</html>";
        $data = ob_get_contents();
        ob_end_clean();
        $fp = fopen($path, "w+");
        fwrite($fp, $data);
        fclose($fp);
    }

    /**
     * 根据HTML代码获取word文档内容
     * 创建一个本质为mht的文档，该函数会分析文件内容并从远程下载页面中的图片资源
     * 该函数依赖于类MhtFileMaker
     * 该函数会分析img标签，提取src的属性值。但是，src的属性值必须被引号包围，否则不能提取
     *
     * @param string $content HTML内容
     * @param string $absolutePath 网页的绝对路径。如果HTML内容里的图片路径为相对路径，那么就需要填写这个参数，来让该函数自动填补成绝对路径。这个参数最后需要以/结束
     * @param bool $isEraseLink 是否去掉HTML内容中的链接
     * by www.jbxue.com
     */
    public static function getWordDocument($content, $absolutePath = "", $isEraseLink = true)
    {
        /*$content = $this->render('test');
        $fileContent = Tools::getWordDocument($content, "http://192.168.1.102:9112/");
        $fp = fopen(\Yii::getAlias('@runtime')."/test.docx", 'w');
        fwrite($fp, $fileContent);
        fclose($fp);*/
        $mht = new MhtFileMaker();
        if ($isEraseLink)
            $content = preg_replace('/<a\s*.*?\s*>(\s*.*?\s*)<\/a>/i', '$1', $content);   //去掉链接

        $images = array();
        $files = array();
        $matches = array();
        //这个算法要求src后的属性值必须使用引号括起来
        if (preg_match_all('/<img[.\n]*?src\s*?=\s*?[\"\'](.*?)[\"\'](.*?)\/>/i', $content, $matches)) {
            $arrPath = $matches[1];
            for ($i = 0; $i < count($arrPath); $i++) {
                $path = $arrPath[$i];
                $imgPath = trim($path);
                if ($imgPath != "") {
                    $files[] = $imgPath;
                    if (substr($imgPath, 0, 7) == 'http://') {
                        //绝对链接，不加前缀
                    } else {
                        $imgPath = $absolutePath . $imgPath;
                    }
                    $images[] = $imgPath;
                }
            }
        }
        $mht->AddContents("tmp.html", $mht->GetMimeType("tmp.html"), $content);

        for ($i = 0; $i < count($images); $i++) {
            $image = $images[$i];
            if (@fopen($image, 'r')) {
                $imgcontent = @file_get_contents($image);
                if ($content)
                    $mht->AddContents($files[$i], $mht->GetMimeType($image), $imgcontent);
            } else {
                echo "file:" . $image . " not exist!<br />";
            }
        }

        return $mht->GetFile();
    }

    /**
     * php获取文件名
     * @param $url
     * @return mixed
     */
    public static function retrieve($url)
    {
        preg_match('/\/([^\/]+\.[a-zA-Z]+)[^\/]*$/', $url, $match);
        return $match[1];
    }

    /**
     * php获取文件扩展名
     * @param $url
     * @return mixed
     */
    public static function getExt($url)
    {
        $path = parse_url($url);
        $str = explode('.', $path['path']);
        return $str[1];
    }

    public static function download($file_url, $new_name = '')
    {
        if (!isset($file_url) || trim($file_url) == '') {
            return '500';
        }
        if (!file_exists($file_url)) { //检查文件是否存在
            return '404';
        }
        $file_name = basename($file_url);
        $file_type = explode('.', $file_url);
        $file_type = $file_type[count($file_type) - 1];
        $file_name = trim($new_name == '') ? $file_name : urlencode($new_name);
        $file_type = fopen($file_url, 'r'); //打开文件
//输入文件标签
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($file_url));
        header("Content-Disposition: attachment; filename=" . $file_name);
//输出文件内容
        echo fread($file_type, filesize($file_url));
        fclose($file_type);
    }

    /**
     * @param $filename 文件名（含路径）
     */
    public static function downloadFile($filename)
    {
        if (!file_exists($filename)) {
            exit("无法找到文件"); //即使创建，仍有可能失败。。。。
        }
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename=' . basename($filename)); //文件名
        header("Content-Type: application/zip"); //zip格式的
        header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件
        header('Content-Length: ' . filesize($filename)); //告诉浏览器，文件大小
        @readfile($filename);
    }

    /**
     * @param string $filename dirpath&name
     */
    public static function createFile($filename)
    {
        if (!file_exists($filename)) {
            $fopen = fopen($filename, 'wb ');//新建文件命令
            fclose($fopen);
        }
    }

    /**
     * 获取文件列表
     * @param $dir
     * @return array
     */
    public static function listDir($dir)
    {
        $result = array();
        if (is_dir($dir)) {
            $file_dir = scandir($dir);
            foreach ($file_dir as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                } elseif (is_dir($dir . $file)) {
                    $result = array_merge($result, self::listDir($dir . $file . '/'));
                } else {
                    array_push($result, $dir . $file);
                }
            }
        }
        return $result;
    }

    /**
     * 备份文件
     * @param string $from_conf
     * @param string $to_conf
     * @param string $log_name_pre
     */
    public static function fileSysMove($from_conf, $to_conf, $log_name_pre = "failed_move_files_")
    {
        /**
         * @var Filesystem $master
         * @var Filesystem $slave
         */
        $master = \Yii::$app->$from_conf;
        $slave = \Yii::$app->$to_conf;
        $slc = $master->listContents('', true);
        foreach ($slc as $k => $v){
            $type = $v['type'];
            $path = $v['path'];
            var_dump($path);
            if ($type != 'dir'){
                if ($slave->has($path)){
                    if ($slave->getSize($path) < $master->getSize($path)){
                        $c = $master->readStream($path);
                        $slave->updateStream($path, $c);
                        var_dump("update");
                    }elseif ($slave->getSize($path) == $master->getSize($path)){
                        var_dump("has");
                    }else{
                        Tools::log($path, $log_name_pre.date("YMD"));
                        var_dump("size error");
                    }
                }else{
                    $c = $master->readStream($path);
                    $slave->writeStream($path, $c);
                    var_dump("write");
                }
            }
        }
    }
}