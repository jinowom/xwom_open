<?php
use common\utils\ToolUtil;
/* @var $this yii\web\View */

$this->title = '接口参数新增与修改';
$pageSize = \Yii::$app->params['pageSize'];
$limitsJson = \Yii::$app->params['limitsJson'];
?>
<style>
    body{background: #ffffff;}
    .layui-form-label{width:108px;}
    .layui-form-item .layui-input-inline{width: 320px;}
    .layui-input-block{width: 315px;}
</style>
<div class="layui-fluid" style="padding: 30px 0px;">
    <div class="layui-row">
        <form method="post" class="layui-form" action="<?=\yii\helpers\Url::toRoute(['third-pary-interface/interface-edit'])?>">
            <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
            <div class="layui-form-item">
                <input name="interface[interfaceid]" type="hidden" value="<?=ToolUtil::getSelectType($interfaceInfo,'interfaceid','')?>" />
                <label for="name" class="layui-form-label" >
                    <span class="x-red">*</span>接口配置名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="interface[name]" value="<?=ToolUtil::getSelectType($interfaceInfo,'name','')?>" placeholder="请输入接口配置名称" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <input name="interface[type]" type="hidden" value="<?=ToolUtil::getSelectType($interfaceInfo,'type','')?>" />
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>平台
                </label>
                <div class="layui-input-block">
                    <select style="width: 100px;" name="interface[type]" lay-filter="type" placeholder="请选择平台" required="" lay-verify="required">
                        <option value="" selected=""></option>
                        <option value="1">微博</option>
                        <option value="2">抖音</option>
                        <option value="3">微信</option>
                        <option value="4">头条</option>
                    </select>
                </div>

            </div>
            <div class="layui-form-item">
                <input name="interface[unitId]" type="hidden" value="<?=ToolUtil::getSelectType($interfaceInfo,'unitId','')?>" />
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>所属单位
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="unitId" name="interface[unitId]" value="<?=ToolUtil::getSelectType($interfaceInfo,'unitId','')?>" placeholder="请选择单位" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="description" class="layui-form-label">
                    <span class="x-red">*</span>appKey
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="clientKey" name="interface[clientKey]" value="<?=ToolUtil::getSelectType($interfaceInfo,'clientKey','')?>" placeholder="请输入appKey" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="description" class="layui-form-label">
                    <span class="x-red">*</span>appSecret
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="clientSecret" name="interface[clientSecret]" value="<?=ToolUtil::getSelectType($interfaceInfo,'clientSecret','')?>" placeholder="请输入appSecret" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="description" class="layui-form-label">
                    <span class="x-red">*</span>回调地址
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="callBackUrl" name="interface[callBackUrl]" value="<?=ToolUtil::getSelectType($interfaceInfo,'callBackUrl','')?>" placeholder="请输入回调地址" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button  class="layui-btn" lay-filter="add" lay-submit="">
                    提交
                </button>
            </div>
        </form>
    </div>
</div>
<script>

</script>
<?php
$getUnitList = \yii\helpers\Url::toRoute(['get-interface-list']);
$update = \yii\helpers\Url::toRoute(['auth/update-interface-status']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$jsStr = <<<STR
    function(value){
       
    }
STR;
//$jsStr = isset($interfaceInfo['password_hash']) ? $jsStr : "[/(.+){6,12}$/, '密码必须6到12位']";

$tableJs = <<<JS
    layui.use(['form', 'layer'],function() {
        $ = layui.jquery;  var form = F(layui.form), layer = layui.layer;
        //自定义验证规则
        form.verify({
            name: function(value) {
                if (value.length < 0) {
                    return '请输入昵称';
                }
            }
        });
        //监听提交
        var sFun = function (jsonData) {
                layer.close(load);
                var ic = (jsonData.status == true) ? icon.ICON_OK : icon.ICON_ERROR;
                layer.msg(jsonData.msg,{icon:ic},function () {
                    if(jsonData.status == true){
                        xadmin.father_reload();
                        xadmin.close();                    
                    }
                });
        };
        form.submit('add','',sFun);
    });
JS;
$this->registerJs($tableJs,\yii\web\View::POS_END);
?>
<script>
</script>