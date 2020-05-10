<?php
namespace moxuandi\helpers;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * Class Uploader 通用上传类
 *
 * @author zhangmoxuan <1104984259@qq.com>
 * @link http://www.zhangmoxuan.com
 * @QQ 1104984259
 * @Date 2019-2-4
 *
 * 说明: 为兼容 UEditor编辑器(http://ueditor.baidu.com), `$config['allowFiles']`中的扩展名全都带有前缀'.', eg: ['.png', '.jpg', '.jpeg'].
 *
 * \yii\imagine\Image 的可用方法:
 * `Image::thumbnail()`: 生成缩略图
 * `Image::crop()`: 裁剪图片
 * `Image::frame()`: 给图片添加边框
 * `Image::watermark()`: 添加图片水印
 * `Image::text()`: 添加文字水印
 * `Image::resize()`: 调整图像大小
 * `Image::autorotate()`:
 */
class Uploader
{
    /**
     * @var array 上传配置信息
     * 可用的数组的键如下:
     * - `allowFiles`: array 允许上传的文件类型, 默认为: ['.png', '.jpg', '.jpeg']. 设置为空数组或`false`时, 将不验证文件类型!
     * - `pathFormat`: string 文件保存路径, 默认为: '/uploads/image/{time}'.
     * - `realName`: string 图片的原始名称, 处理 base64 编码的图片时有效.
     * - `process`: false|array 图片处理配置, 二维数组. 设置为`false`时不处理图片; 设置为二维数组时, 有以下可用值:
     *   - `match`: array 图片处理后保存路径的替换规则, 必须是两个元素的数组, 默认为: ['image', 'process']. 注意, 当两个元素的值相同时, 将不会保存原图, 而仅保留处理后的图片.
     *   - `thumb`: false|array 缩略图配置. 有以下可用值:
     *     * `width`: int 缩略图的宽度.
     *     * `height`: int 缩略图的高度.
     *     * `mode`: string 生成缩略图的模式, 可用值: 'inset'(补白), 'outbound'(裁剪, 默认值).
     *   - `crop`: false|array 裁剪图像配置. 有以下可用值:
     *     * `width`: int 裁剪图的宽度.
     *     * `height`: int 裁剪图的高度.
     *     * `top`: int 裁剪图顶部的偏移, y轴起点, 默认为`0`.
     *     * `left`: int 裁剪图左侧的偏移, x轴起点, 默认为`0`.
     *   - `frame`: false|array 添加边框的配置. 有以下可用值:
     *     * `margin`: int 边框的宽度, 默认为`20`.
     *     * `color`: string 边框的颜色, 十六进制颜色编码, 可以不带`#`, 默认为`666`.
     *     * `alpha`: int 边框的透明度, 可能仅`png`图片生效, 默认为`100`.
     *   - `watermark`: false|array 添加图片水印的配置. 有以下可用值:
     *     * `watermarkImage`: string 水印图片的绝对路径.
     *     * `top`: int 水印图片的顶部距离原图顶部的偏移, y轴起点, 默认为`0`.
     *     * `left`: int 水印图片的左侧距离原图左侧的偏移, x轴起点, 默认为`0`.
     *   - `text`: false|array 添加文字水印的配置. 有以下可用值:
     *     * `text`: string 水印文字的内容.
     *     * `fontFile`: string 字体文件, 可以是绝对路径或别名.
     *     * `top`: int 水印文字的顶部距离原图顶部的偏移, y轴起点, 默认为`0`.
     *     * `left`: int 水印文字的左侧距离原图左侧的偏移, x轴起点, 默认为`0`.
     *     * `fontOptions`: array 字体属性, 支持以下三个值:
     *       * `size`: int 字体的大小, 单位像素(`px`), 默认为`12`.
     *       * `color`: string 字体的颜色, 十六进制颜色编码, 可以不带`#`, 默认为`fff`.
     *       * `angle`: int 写入文本的角度, 默认为`0`.
     *   - `resize`: false|array 调整图片大小的配置. 有以下可用值:
     *     * `width`: int 图片调整后的宽度.
     *     * `height`: int 图片调整后的高度.
     *     * `keepAspectRatio`: bool 是否保持图片纵横比, 默认为`true`.
     *     * 如果设置为`true`, 图片将在不超过`width`和`height`的情况下, 等比例缩放;
     *     * `allowUpscaling`: bool 如果原图很小, 图片是否放大, 默认为`false`.
     */
    public $config = [];
    /**
     * @var UploadedFile|null 上传对象
     */
    public $file;
    /**
     * @var string 原始文件名
     */
    public $realName;
    /**
     * @var string 新文件名
     */
    public $fileName;
    /**
     * @var string 完整的文件名(带路径)
     */
    public $fullName;
    /**
     * @var string 经过处理后的文件名(带路径)
     */
    public $processName;
    /**
     * @var int 文件大小, 单位:B
     */
    public $fileSize;
    /**
     * @var string 文件的 MIME 类型(eg "image/gif").
     */
    public $fileType;
    /**
     * @var string 文件扩展名(eg "jpg").
     */
    public $fileExt;
    /**
     * @var string 根目录绝对路径
     */
    public $rootPath;
    /**
     * @var string 根目录的URL
     */
    public $rootUrl;
    /**
     * @var string 分片暂存区
     */
    public $chunkPath = '/uploads/chunks';
    /**
     * @var array 分片上传进度
     */
    public $chunkProgress = [];
    /**
     * @var int|string 上传状态
     */
    public $status;
    /**
     * @var null|yii\db\ActiveRecord
     */
    public $uploadModel;


    /**
     * Uploader constructor.
     * @param string $fileField 文件上传域名称, eg: 'upfile'.
     * @param array $config 上传配置信息.
     * @param string $type 上传类型, 可用值: 'remote'(拉取远程图片), 'base64'(处理base64编码的图片上传), 'upload'(普通上传, 默认值).
     * @throws Exception
     */
    public function __construct($fileField, $config = [], $type = 'upload')
    {
        $_config = [
            'allowFiles' => ['.png', '.jpg', '.jpeg'],  // 上传图片格式显示
            'pathFormat' => '/uploads/image/{time}',    // 上传保存路径
            'realName' => 'scrawl.png',                 // base64 编码的图片的默认名称
            'process' => false,                         // 二维数组, 将按照子数组的顺序对图片进行处理
            'modelClass' => null,                       // 文件信息是否保存入库
        ];
        $this->rootPath = ArrayHelper::remove($config, 'rootPath', dirname(Yii::$app->request->scriptFile));
        $this->rootUrl = ArrayHelper::remove($config, 'rootUrl', Yii::$app->request->hostInfo);
        $this->config = array_merge($_config, $config);  // 不使用 ArrayHelper::merge() 方法, 是因为其会递归合并数组.
        $this->file = UploadedFile::getInstanceByName($fileField);  // 获取上传对象

        switch($type){
            case 'remote': $result = $this->uploadFile(); break;
            case 'base64': $result = $this->uploadBase64($fileField); break;
            case 'upload':
            default: $result = $this->uploadHandle(); break;
        }
        if($result){
            $this->status = 0;
            return true;
        }
        return false;
    }

    /**
     * 分离大文件分片上传与普通上传.
     * @return bool
     * @throws Exception
     */
    public function uploadHandle()
    {
        return Yii::$app->request->post('chunks') ? $this->uploadChunkFile() : $this->uploadFile();
    }

    /**
     * 普通文件上传.
     * @return bool
     * @throws Exception
     */
    public function uploadFile()
    {
        // 检查上传对象是否为空
        if(empty($this->file)){
            $this->status = 4;
            return false;
        }

        // 校验 $_FILES['upfile']['error'] 的错误
        if($this->file->error){
            $this->status = $this->file->error;
            return false;
        }

        // 检查临时文件是否存在
        if(!file_exists($this->file->tempName)){
            $this->status = 'ERROR_TMP_FILE_NOT_FOUND';
            return false;
        }

        // 检查文件是否是通过 HTTP POST 上传的
        if(!is_uploaded_file($this->file->tempName)){
            $this->status = 'ERROR_HTTP_UPLOAD';
            return false;
        }

        $this->realName = $this->file->name;
        $this->fileSize = $this->file->size;
        $this->fileType = $this->file->type;
        $this->fileExt = $this->file->extension;

        // 检查文件类型(扩展名)是否符合网站要求
        if($this->config['allowFiles'] && !in_array('.' . $this->fileExt, $this->config['allowFiles'])){
            $this->status = 'ERROR_TYPE_NOT_ALLOWED';
            return false;
        }

        $this->fullName = Helper::getFullName($this->realName, $this->config['pathFormat'], $this->fileExt);
        $this->fileName = StringHelper::basename($this->fullName);

        // 创建目录
        $fullPath = FileHelper::normalizePath($this->rootPath . DIRECTORY_SEPARATOR . $this->fullName);  // 文件在磁盘上的绝对路径
        if(!FileHelper::createDirectory(dirname($fullPath))){
            $this->status = 'ERROR_CREATE_DIR';
            return false;
        }

        // 保存上传文件
        if($this->file->saveAs($fullPath)){
            // 对图片进一步处理
            if(!$this->processImage($this->config['process'], $fullPath)){
                return false;
            }

            // 保存文件信息入库
            if($this->config['modelClass'] && !$this->saveDatabase($fullPath)){
                return false;
            }

            return true;
        }else{
            $this->status = 'ERROR_FILE_MOVE';
            return false;
        }
    }

    /**
     * 大文件分片上传
     * @return bool
     * @throws Exception
     */
    public function uploadChunkFile()
    {
        // 检查上传对象是否为空
        if(empty($this->file)){
            $this->status = 4;
            return false;
        }

        // 校验 $_FILES['upfile']['error'] 的错误
        if($this->file->error){
            $this->status = $this->file->error;
            return false;
        }

        // 检查临时文件是否存在
        if(!file_exists($this->file->tempName)){
            $this->status = 'ERROR_TMP_FILE_NOT_FOUND';
            return false;
        }

        // 检查文件是否是通过 HTTP POST 上传的
        if(!is_uploaded_file($this->file->tempName)){
            $this->status = 'ERROR_HTTP_UPLOAD';
            return false;
        }

        $post = Yii::$app->request->post();  // 接收分片信息
        $this->realName = $post['name'];
        $this->fileSize = $post['size'];
        $this->fileType = $post['type'];
        $this->fileExt = Helper::getExtension($this->realName);  // 不使用`$this->file->extension`, 因为`$this->file->name`的值可能是`blob`
        $this->chunkProgress = [
            'chunk' => $post['chunk'],
            'chunks' => $post['chunks'],
            'percent' => round($post['chunk'] / $post['chunks'] * 100) . '%',
        ];

        // 检查文件类型(扩展名)是否符合网站要求
        if($this->config['allowFiles'] && !in_array('.' . $this->fileExt, $this->config['allowFiles'])){
            $this->status = 'ERROR_TYPE_NOT_ALLOWED';
            return false;
        }

        // 保存分片
        $chunkName = FileHelper::normalizePath($this->rootPath . DIRECTORY_SEPARATOR . $this->chunkPath . DIRECTORY_SEPARATOR . md5($this->realName) . '.' . $this->fileExt);
        if(!FileHelper::createDirectory(dirname($chunkName))){
            $this->status = 'ERROR_CREATE_DIR';
            return false;
        }
        if($this->chunkProgress['chunk'] == 1){
            file_put_contents($chunkName, file_get_contents($this->file->tempName));  // 新建并保存
        }else{
            file_put_contents($chunkName, file_get_contents($this->file->tempName), FILE_APPEND);  // 追加写入文件
        }
        //FileHelper::unlink($this->file->tempName);  // 删除缓存文件

        // 分片全部上传完成, 并且分片暂存区保存有所有分片
        if($this->chunkProgress['chunk'] == $this->chunkProgress['chunks']){
            $this->fullName = Helper::getFullName($this->realName, $this->config['pathFormat'], $this->fileExt);
            $this->fileName = StringHelper::basename($this->fullName);

            // 创建目录
            $fullPath = FileHelper::normalizePath($this->rootPath . DIRECTORY_SEPARATOR . $this->fullName);  // 文件在磁盘上的绝对路径
            if(!FileHelper::createDirectory(dirname($fullPath))){
                $this->status = 'ERROR_CREATE_DIR';
                return false;
            }

            rename($chunkName, $fullPath);  // 移动文件, 从分片暂存区移动到真实位置

            // 对图片进一步处理
            if(!$this->processImage($this->config['process'], $fullPath)){
                return false;
            }

            // 保存文件信息入库
            if($this->config['modelClass'] && !$this->saveDatabase($fullPath)){
                return false;
            }

            //$this->file->size = $this->fileSize;  // 重置上传对象的大小, 可选
            //$this->file->type = $this->fileType;  // 重置上传对象的MIME类型, 可选
            return true;
        }else{
            $this->status = 'ERROR_CHUNK_DEFECT';
            return false;
        }
    }

    /**
     * 处理 base64 编码的图片上传(主要是 UEditor 编辑器的涂鸦功能).
     * @param string $fileField 文件上传域名称, eg: 'upfile'.
     * @return bool
     * @throws Exception
     */
    public function uploadBase64($fileField)
    {
        $base64Data = Yii::$app->request->post($fileField);
        $baseImg = base64_decode($base64Data);  // 解码图片数据

        $this->realName = $this->config['realName'];
        $this->fileSize = strlen($baseImg);
        $this->fileType = 'image/png';
        $this->fileExt = Helper::getExtension($this->realName);
        $this->fullName = Helper::getFullName($this->realName, $this->config['pathFormat'], $this->fileExt);
        $this->fileName = StringHelper::basename($this->fullName);

        // 创建目录
        $fullPath = FileHelper::normalizePath($this->rootPath . DIRECTORY_SEPARATOR . $this->fullName);  // 文件在磁盘上的绝对路径
        if(!FileHelper::createDirectory(dirname($fullPath))){
            $this->status = 'ERROR_CREATE_DIR';
            return false;
        }

        // 将图片数据写入文件, 并检查文件是否存在.
        if(file_put_contents($fullPath, $baseImg) && file_exists($fullPath)){
            // 保存文件信息入库
            if($this->config['modelClass'] && !$this->saveDatabase($fullPath)){
                return false;
            }
            return true;
        }else{
            $this->status = 'ERROR_WRITE_CONTENT';
            return false;
        }
    }

    /**
     * 对图片进一步处理
     * @param array $process 图片处理配置.
     * @param string $tempName 图片的绝对路径, 或上传图片的临时文件的路径.
     * @return bool
     * @throws Exception
     */
    public function processImage($process, $tempName)
    {
        // 先过滤掉不需要进行图片处理的文件
        if(empty($process) || !in_array($this->fileExt, ['jpg', 'jpeg', 'png', 'gif'])){
            return true;
        }

        // 处理后图片的路径
        list($imageStr, $processStr) = ArrayHelper::remove($process, 'match', ['image', 'process']);
        $this->processName = str_replace($imageStr, $processStr, $this->fullName);
        $processPath = FileHelper::normalizePath($this->rootPath . DIRECTORY_SEPARATOR . $this->processName);  // 文件在磁盘上的绝对路径

        // 创建目录
        if(!FileHelper::createDirectory(dirname($processPath))){
            $this->status = 'ERROR_CREATE_DIR';
            return false;
        }

        // 对图片进行处理, 每处理一次就保存一次
        $first = true;
        foreach($process as $key => $config){
            switch($key){
                case 'thumb':
                    if(!$this->makeThumb($first ? $tempName : $processPath, $processPath, $config)){
                        return false;
                    }
                    $first = false;
                    break;
                case 'crop':
                    if(!$this->cropImage($first ? $tempName : $processPath, $processPath, $config)){
                        return false;
                    }
                    $first = false;
                    break;
                case 'frame':
                    if(!$this->frameImage($first ? $tempName : $processPath, $processPath, $config)){
                        return false;
                    }
                    $first = false;
                    break;
                case 'watermark':
                    if(!$this->watermarkImage($first ? $tempName : $processPath, $processPath, $config)){
                        return false;
                    }
                    $first = false;
                    break;
                case 'text':
                    if(!$this->textImage($first ? $tempName : $processPath, $processPath, $config)){
                        return false;
                    }
                    $first = false;
                    break;
                case 'resize':
                    if(!$this->resizeImage($first ? $tempName : $processPath, $processPath, $config)){
                        return false;
                    }
                    $first = false;
                    break;
                default: break;
            }
        }
        return true;
    }

    /**
     * 生成缩略图.
     * @param string $tempName 处理前图片的绝对路径, 或上传图片的临时文件的路径.
     * @param string $processPath 处理后图片的绝对路径.
     * @param array $config 处理配置.
     * @return bool 缩略图生成失败时, Image 会抛出异常.
     */
    public function makeThumb($tempName, $processPath, $config)
    {
        $width = ArrayHelper::getValue($config, 'width');
        $height = ArrayHelper::getValue($config, 'height');
        $mode = ArrayHelper::getValue($config, 'mode', 'outbound');

        // 判断宽度和高度, 不能同时为`null`
        if(!$width && !$height){
            $this->status = 'ERROR_CREATE_THUMB';
            return false;
        }

        // 生成并保存缩略图
        Image::thumbnail($tempName, $width, $height, $mode)->save($processPath);
        return true;
    }

    /**
     * 生成裁剪图
     * @param string $tempName 处理前图片的绝对路径, 或上传图片的临时文件的路径.
     * @param string $processPath 处理后图片的绝对路径.
     * @param array $config 处理配置.
     * @return bool 裁剪图生成失败时, Image 会抛出异常.
     */
    public function cropImage($tempName, $processPath, $config)
    {
        $width = ArrayHelper::getValue($config, 'width');
        $height = ArrayHelper::getValue($config, 'height');
        $top = ArrayHelper::getValue($config, 'top', 0);
        $left = ArrayHelper::getValue($config, 'left', 0);

        // 判断宽度和高度, 都不能为`0`或负值
        if($width <= 0 || $height <= 0){
            $this->status = 'ERROR_CROP_IMAGE';
            return false;
        }

        // 生成并保存裁剪图
        Image::crop($tempName, $width, $height, [$left, $top])->save($processPath);
        return true;
    }

    /**
     * 给图片添加边框
     * @param string $tempName 处理前图片的绝对路径, 或上传图片的临时文件的路径.
     * @param string $processPath 处理后图片的绝对路径.
     * @param array $config 处理配置.
     * @return bool
     */
    public function frameImage($tempName, $processPath, $config)
    {
        $margin = ArrayHelper::getValue($config, 'margin', 20);
        $color = ArrayHelper::getValue($config, 'color', '666');
        $alpha = ArrayHelper::getValue($config, 'alpha', 100);

        // 生成并保存添加边框后的图片
        Image::frame($tempName, $margin, $color, $alpha)->save($processPath);
        return true;
    }

    /**
     * 给图片添加图片水印
     * @param string $tempName 处理前图片的绝对路径, 或上传图片的临时文件的路径.
     * @param string $processPath 处理后图片的绝对路径.
     * @param array $config 处理配置.
     * @return bool
     */
    public function watermarkImage($tempName, $processPath, $config)
    {
        $watermarkImage = ArrayHelper::getValue($config, 'watermarkImage');
        $top = ArrayHelper::getValue($config, 'top', 0);
        $left = ArrayHelper::getValue($config, 'left', 0);

        // 判断水印图片是否存在
        if($watermarkImage && is_file($watermarkImage)){
            $this->status = 'ERROR_FILE_NOT_FOUND';
            return false;
        }

        // 生成并保存添加图片水印后的图片
        Image::watermark($tempName, $watermarkImage, [$left, $top])->save($processPath);
        return true;
    }

    /**
     * 给图片添加文字水印
     * @param string $tempName 处理前图片的绝对路径, 或上传图片的临时文件的路径.
     * @param string $processPath 处理后图片的绝对路径.
     * @param array $config 处理配置.
     * @return bool
     */
    public function textImage($tempName, $processPath, $config)
    {
        $text = ArrayHelper::getValue($config, 'text');
        $fontFile = ArrayHelper::getValue($config, 'fontFile');
        $top = ArrayHelper::getValue($config, 'top', 0);
        $left = ArrayHelper::getValue($config, 'left', 0);
        $fontOptions = ArrayHelper::getValue($config, 'fontOptions', []);

        // 判断水印文字是否为空
        if(!$text){
            $this->status = 'ERROR_NO_TEXT';
            return false;
        }

        // 判断字体文件是否存在
        if($fontFile && is_file($fontFile)){
            $this->status = 'ERROR_NO_FONT_FILE';
            return false;
        }

        // 生成并保存添加文字水印后的图片
        Image::text($tempName, $text, $fontFile, [$left, $top], $fontOptions)->save($processPath);
        return true;
    }

    /**
     * 调整图片大小
     * @param string $tempName 处理前图片的绝对路径, 或上传图片的临时文件的路径.
     * @param string $processPath 处理后图片的绝对路径.
     * @param array $config 处理配置.
     * @return bool
     */
    public function resizeImage($tempName, $processPath, $config)
    {
        $width = ArrayHelper::getValue($config, 'width');
        $height = ArrayHelper::getValue($config, 'height');
        $keepAspectRatio = ArrayHelper::getValue($config, 'keepAspectRatio', true);
        $allowUpscaling = ArrayHelper::getValue($config, 'allowUpscaling', false);

        // 判断宽度和高度, 不能同时为`null`
        if(!$width && !$height){
            $imgInfo = Helper::getImageInfo($tempName, true);
            $width = $imgInfo['width'];
            $height = $imgInfo['height'];
        }

        // 生成并保存调整图片大小后的图片
        Image::resize($tempName, $width, $height, $keepAspectRatio, $allowUpscaling)->save($processPath);
        return true;
    }

    /**
     * 保存文件信息入库
     * @param string|null $fullPath 文件在磁盘上的绝对路径
     * @return bool
     */
    public function saveDatabase($fullPath = null)
    {
        /* @var $model yii\db\ActiveRecord */
        $model = new $this->config['modelClass'];
        if(!is_subclass_of($model, 'yii\db\ActiveRecord')){
            $this->status = 'ERROR_MODEL_CLASS';
            return false;
        }

        // 构建模型数据
        $data = [
            'real_name' => $this->realName,
            'file_name' => $this->fileName,
            'full_name' => $this->fullName,
            'process_name' => $this->processName,
            'file_size' => $this->fileSize,
            'file_type' => $this->fileType,
            'file_ext' => $this->fileExt,
        ];
        if($fullPath){
            $data['file_md5'] = md5_file($fullPath);
            $data['file_sha1'] = sha1_file($fullPath);
        }

        $model->load($data, '');
        if($model->save()){
            $this->uploadModel = $model;
            return true;
        }else{
            $this->status = 'ERROR_SAVE_DATABASE';
            return false;
        }
    }


    /**
     * @var array 上传状态映射表, 国际化用户需考虑此处数据的国际化.
     * @see http://www.php.net/manual/en/features.file-upload.errors.php
     */
    static $stateMap = [
        0 => 'SUCCESS',  // UPLOAD_ERR_OK, 上传成功标记, 在`UEditor`中内不可改变, 否则`flash`判断会出错
        1 => '文件大小超出 php.ini 中的 upload_max_filesize 限制' ,  // UPLOAD_ERR_INI_SIZE
        2 => '文件大小超出 HTML 表单中的 MAX_FILE_SIZE 限制' ,  // UPLOAD_ERR_FORM_SIZE
        3 => '文件未被完整上传' ,  // UPLOAD_ERR_PARTIAL
        4 => '没有文件被上传' ,  // UPLOAD_ERR_NO_FILE
        6 => '临时文件夹不存在' ,  // UPLOAD_ERR_NO_TMP_DIR
        7 => '无法将文件写入磁盘',  // UPLOAD_ERR_CANT_WRITE
        8 => '因 php 扩展停止文件上传',  // UPLOAD_ERR_EXTENSION
        //'ERROR_TMP_FILE' => '临时文件错误',
        'ERROR_TMP_FILE_NOT_FOUND' => '找不到临时文件',
        'ERROR_TYPE_NOT_ALLOWED' => '文件类型不允许',
        'ERROR_CREATE_DIR' => '目录创建失败',
        'ERROR_DIR_NOT_WRITEABLE' => '目录没有写入权限',
        'ERROR_FILE_MOVE' => '文件保存时出错',
        //'ERROR_FILE_NOT_FOUND' => '找不到上传文件',
        'ERROR_WRITE_CONTENT' => '写入文件内容错误',
        'ERROR_HTTP_UPLOAD' => '非法上传',
        'ERROR_CHUNK_DEFECT' => '分片不完整',
        'ERROR_UNKNOWN' => '未知错误',
        //'ERROR_DEAD_LINK' => '链接不可用',
        //'ERROR_HTTP_LINK' => '链接不是http链接',
        //'ERROR_HTTP_CONTENTTYPE' => '链接contentType不正确',
        'ERROR_SAVE_DATABASE' => '文件上传成功，但在保存到数据库时失败',
        'ERROR_MODEL_CLASS' => '`modelClass`必须继承自`yii\db\ActiveRecord`或其子类',
        'ERROR_CREATE_THUMB' => '生成缩略图时, 宽度和高度不能同时为`null`',
        'ERROR_CROP_IMAGE' => '生成裁剪图时, 宽度和高度都不能为`0`或负值',
        'ERROR_FILE_NOT_FOUND' => '添加图片水印时, 未找到水印图片',
        'ERROR_NO_FONT_FILE' => '添加文字水印时, 未找到字体文件',
        'ERROR_NO_TEXT' => '添加文字水印时, 水印文字不能为空',
    ];
}
