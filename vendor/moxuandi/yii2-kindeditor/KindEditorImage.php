<?php
namespace moxuandi\kindeditor;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\InputWidget;

/**
 * KindEditor renders a editor js plugin for classic editing.
 *
 * @author zhangmoxuan <1104984259@qq.com>
 * @link http://www.zhangmoxuan.com
 * @QQ 1104984259
 * @Date 2019-2-8
 * @see http://kindeditor.net
 */
class KindEditorImage extends InputWidget
{
    /**
     * @var array 配置接口, 参阅[KindEditor 官方文档](http://kindeditor.net/docs/option.html)
     */
    public $editorOptions = [];
    /**
     * @var string 编辑器的类型, 可用值有:
     * - `imageDialog`: 上传图片(网络图片 + 本地上传)
     * - `remoteImageDialog`: 上传图片(网络图片)
     * - `localImageDialog`: 上传图片(本地上传)
     */
    public $editorType = 'imageDialog';
    /**
     * @var array input 输入域的 HTML 属性
     */
    public $options = [];
    /**
     * @var array button 按钮的 HTML 属性. 特殊属性:
     * - `label`: button 按钮的文本内容(不是 label 属性)
     * - `resetLabel`: button 按钮的文本内容(重新选择)
     */
    public $buttonOptions = ['class' => 'btn btn-primary'];
    /**
     * @var array 最外层 div 的 HTML 属性
     */
    public $wrapOptions = ['class' => 'image_dialog'];
    /**
     * @var string 视图名称
     */
    public $viewName = 'image_dialog';


    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if(!in_array($this->editorType, ['imageDialog', 'remoteImageDialog', 'localImageDialog'])){
            throw new InvalidConfigException('不支持的 $editorType 值.');
        }

        $this->editorOptions = array_merge([
            'allowFileManager' => true,  // 显示浏览远程服务器按钮
            'uploadJson' => Url::to(['Kupload', 'action'=>'uploadJson']),  // 指定上传文件的服务器端程序
            'fileManagerJson' => Url::to(['Kupload', 'action'=>'fileManagerJson']),  // 指定浏览远程图片的服务器端程序
        ], $this->editorOptions);

        if($this->hasModel()){
            $this->id = $this->id . '_' . $this->options['id'];
            echo Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        }else{
            $this->options['id'] = $this->id . '_' . $this->name;
            echo Html::hiddenInput($this->name, $this->value, $this->options);
        }

        $this->wrapOptions = array_merge(['id' => $this->id], $this->wrapOptions);
        Html::addCssClass($this->buttonOptions, 'btn-select');
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render($this->viewName, [
            'editorOptions' => Json::encode($this->editorOptions),
            'buttonOptions' => $this->buttonOptions,
            'wrapOptions' => $this->wrapOptions,
            'inputId' => $this->options['id'],
            'inputValue' => $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value,
            'showLocal' => in_array($this->editorType, ['imageDialog', 'localImageDialog']) ? 'true' : 'false',
            'showRemote' => in_array($this->editorType, ['imageDialog', 'remoteImageDialog']) ? 'true' : 'false',
        ]);
    }
}
