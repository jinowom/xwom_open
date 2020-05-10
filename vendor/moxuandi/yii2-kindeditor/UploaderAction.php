<?php
namespace moxuandi\kindeditor;

use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\Response;
use moxuandi\helpers\Uploader;

/**
 * KindEditor 接收上传图片控制器.
 *
 * @author zhangmoxuan <1104984259@qq.com>
 * @link http://www.zhangmoxuan.com
 * @QQ 1104984259
 * @Date 2019-2-8
 * @see http://kindeditor.net
 */
class UploaderAction extends Action
{
    /**
     * @var array 上传配置信息接口
     */
    public $config = [];


    public function init()
    {
        parent::init();
        Yii::$app->request->enableCsrfValidation = false;  // 关闭csrf
        $_config = require(__DIR__ . '/config.php');  // 加载默认上传配置
        $this->config = array_merge($_config, $this->config);
    }

    /**
     * @throws Exception
     */
    public function run()
    {
        $request = Yii::$app->request;
        switch($request->get('action')){
            case 'fileManagerJson': $this->actionList($request->get('dir')); break;
            case 'uploadJson': $this->actionUpload($request->get('dir')); break;
            default: break;
        }
    }

    /**
     * 处理上传
     * @param string $dir
     * @throws Exception
     */
    public function actionUpload($dir)
    {
        switch($dir){
            case 'image':  // 图片
                $config = [
                    'allowFiles' => $this->config['imageAllowFiles'],
                    'pathFormat' => $this->config['imagePathFormat'],
                    'process' => ArrayHelper::getValue($this->config, 'process', false),
                ];
                break;
            case 'flash':  // Flash
                $config = [
                    'allowFiles' => $this->config['flashAllowFiles'],
                    'pathFormat' => $this->config['flashPathFormat'],
                ];
                break;
            case 'media':  // 音频
                $config = [
                    'allowFiles' => $this->config['mediaAllowFiles'],
                    'pathFormat' => $this->config['mediaPathFormat'],
                ];
                break;
            case 'file':  // 文件
                $config = [
                    'allowFiles' => $this->config['fileAllowFiles'],
                    'pathFormat' => $this->config['filePathFormat'],
                ];
                break;
            default:
                $config = [];
                break;
        }

        $config['rootPath'] = ArrayHelper::getValue($this->config, 'rootPath', dirname(Yii::$app->request->scriptFile));
        $config['rootUrl'] = ArrayHelper::getValue($this->config, 'rootUrl', Yii::$app->request->hostInfo);
        $config['modelClass'] = ArrayHelper::getValue($this->config, 'modelClass');

        // 生成上传实例对象并完成上传, 返回结果数据
        $upload = new Uploader('imgFile', $config);

        // 输出响应结果
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        if($upload->status){
            $response->data = [
                'error' => 1,
                'message' => Uploader::$stateMap[$upload->status],
            ];
        }else{
            $response->data = [
                'error' => 0,
                'url' => $upload->fullName,
                'title' => substr($upload->realName, 0, strripos($upload->realName, '.')),
            ];
        }
        $response->send();
    }

    /**
     * 列出已上传的文件列表
     * @param string $dir
     * @throws Exception
     */
    public function actionList($dir)
    {
        if(!in_array($dir, ['image', 'flash', 'media', 'file'])){
            //echo "Invalid Directory name.";
            echo '无效的目录名称。';
            exit();
        }

        $rootPath = ArrayHelper::getValue($this->config, 'rootPath', dirname(Yii::$app->request->scriptFile));
        $rootUrl = ArrayHelper::getValue($this->config, 'rootUrl', Yii::$app->request->hostInfo);
        switch($dir){
            case 'image':  // 图片
                $rootPath .= $this->config['imageRootPath'];
                $rootUrl .= $this->config['imageRootPath'];
                break;
            case 'flash':  // Flash
                $rootPath .= $this->config['flashRootPath'];
                $rootUrl .= $this->config['flashRootPath'];
                break;
            case 'media':  // 视音频
                $rootPath .= $this->config['mediaRootPath'];
                $rootUrl .= $this->config['mediaRootPath'];
                break;
            case 'file':  // 文件
                $rootPath .= $this->config['fileRootPath'];
                $rootUrl .= $this->config['fileRootPath'];
                break;
            default:
                $rootPath = $rootUrl = '';
                break;
        }

        if(!file_exists($rootPath)){
            FileHelper::createDirectory($rootPath);  // 创建目录
        }

        // 根据 path 参数, 设置各路径和 URL
        $path = Yii::$app->request->get('path');
        if(empty($path)){
            $currentPath = realpath($rootPath) . '/';
            $currentUrl = $rootUrl;
            $currentDirPath = '';
            $moveUpDirPath = '';
        }else{
            $currentPath = realpath($rootPath) . '/' . $path;
            $currentUrl = $rootUrl . $path;
            $currentDirPath = $path;
            $moveUpDirPath = preg_replace('/(.*?)[^\/]+\/$/', '$1', $currentDirPath);
        }

        // 不允许使用'..'移动到上一级目录
        if(preg_match('/\.\./', $currentPath)){
            //echo 'Access is not allowed.';
            echo '访问不被允许。';
            exit;
        }

        // 如果最后一个字符不是'/'
        if(!preg_match('/\/$/', $currentPath)){
            //echo 'Parameter is not valid.';
            echo '参数无效。';
            exit;
        }

        // 目录不存在或不是目录
        if(!file_exists($currentPath) || !is_dir($currentPath)){
            //echo 'Directory does not exist.';
            echo '目录不存在。';
            exit;
        }

        // 遍历目录取得文件信息
        $fileList = [];
        if($handle = opendir($currentPath)){
            $i = 0;
            while(false !== ($filename = readdir($handle))){
                if($filename[0] == '.') continue;
                $file = $currentPath . $filename;
                if(is_dir($file)){
                    $fileList[$i]['is_dir'] = true;  // 是否文件夹
                    $fileList[$i]['has_file'] = (count(scandir($file)) > 2);  // 文件夹是否包含文件
                    $fileList[$i]['filesize'] = 0;  // 文件大小
                    $fileList[$i]['is_photo'] = false;  // 是否是图片
                    $fileList[$i]['filetype'] = '';  // 文件类别, 用扩展名判断
                }else{
                    $fileList[$i]['is_dir'] = false;
                    $fileList[$i]['has_file'] = false;
                    $fileList[$i]['filesize'] = filesize($file);  // 文件大小
                    $fileList[$i]['dir_path'] = '';
                    $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));  // 小写的扩展名
                    $fileList[$i]['is_photo'] = in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'bmp']);  // 是否是图片
                    $fileList[$i]['filetype'] = $fileExt;  // 文件类别, 用扩展名判断
                }
                $fileList[$i]['filename'] = $filename;  //文件名, 包含扩展名
                $fileList[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file));  // 文件最后修改时间
                $i++;
            }
            closedir($handle);
        }

        //用 cmp_func() 函数对数组进行排序
        usort($fileList, [$this, 'cmp_func']);

        // 输出响应结果
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data = [
            'moveup_dir_path' => $moveUpDirPath,    // 相对于根目录的上一级目录
            'current_dir_path' => $currentDirPath,  // 相对于根目录的当前目录
            'current_url' => $currentUrl,           // 当前目录的URL
            'total_count' => count($fileList),      // 文件总数
            'file_list' => $fileList                // 文件列表
        ];
        $response->send();
    }

    /**
     * 排序
     * @param array $a
     * @param array $b
     * @return int
     */
    public function cmp_func($a, $b)
    {
        $order = strtolower(Yii::$app->request->get('order', 'name'));  // 排序形式, name or size or type
        if($a['is_dir'] && !$b['is_dir']){
            return -1;
        }elseif(!$a['is_dir'] && $b['is_dir']){
            return 1;
        }else{
            if($order == 'size'){
                if($a['filesize'] > $b['filesize']){
                    return 1;
                }elseif($a['filesize'] < $b['filesize']){
                    return -1;
                }else{
                    return 0;
                }
            }elseif($order == 'type'){
                return strcmp($a['filetype'], $b['filetype']);
            }else{
                return strcmp($a['filename'], $b['filename']);
            }
        }
    }
}
