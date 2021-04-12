<?php
/**
 * Created by PhpStorm.
 * User: WANGWEIHUA
 * Date: 2020/4/22
 * Time: 19:47
 */
namespace backend\actions;
use backend\modules\xedit\models\XedNewsFile;
use common\utils\ToolUtil;
use yii\base\Action;

class UploadAction extends Action
{
    //这里面的三个参数的值是通过控制器actions中配置而来的
    public $files;
    public $limitRules;  //限制规则
    public $is;  //是否入库


    public function run(){
        if(!empty($this->limitRules) && !in_array($this->files->extension,$this->limitRules)){
            return ToolUtil::returnAjaxMsg(false,'附件格式不符合！');
        }
        $fileName = "file_".time()."_".rand(1111,9999).".".$this->files->extension;
        $dir = "upload/" .date("Y").'/'.date("m").'/'.date("d").'/';
        $filePath = $dir . $fileName;
        if (!is_dir(\Yii::$app->basePath . "/web/".$dir)) {
            if (!@mkdir($dir,0777,true)) throw  new \Exception('Create folder failed ');
            //修改权限
            @chmod($dir, 0777);
        }
        $res = $this->files->saveAs($filePath);
        if($res && !empty($this->is)){
            $fileModel = new XedNewsFile();
            $fileRes = $fileModel->create([
                'new_id' => 0,
                'file_name' => $this->files->name,
                'file_caption' => '',
                'file_type' => $this->files->extension,
                'file_path' => $filePath,
                'file_size' => $this->files->size
            ]);
            if($fileRes['status']){
                return ToolUtil::returnAjaxMsg(true,'上传成功',['filePath' => $fileRes['msg']]);
            }
            return ToolUtil::returnAjaxMsg(false,'上传失败');
        }else{
            if($res){
                return ToolUtil::returnAjaxMsg(true,$filePath);
            }
            $error = ($this->files->error) ? $this->files->error : '上传失败';
            return ToolUtil::returnAjaxMsg(false,$error);
        }
    }
}