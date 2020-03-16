<?php
use common\utils\ToolUtil;
/* @var $this yii\web\View */

$this->title = '部门编辑';
$pageSize = \Yii::$app->params['pageSize'];
$limitsJson = \Yii::$app->params['limitsJson'];
?>
<style>
    body{background: #ffffff;}
</style>
<div class="layui-fluid" style="padding: 30px 0px;">
    <div class="layui-row">
        <form method="post" class="layui-form" action="<?=\yii\helpers\Url::toRoute(['dep/dep-edit'])?>">
            <div class="layui-form-item">
                <input name="dep[depid]" type="hidden" value="<?=ToolUtil::getSelectType($depInfo,'depid','')?>" />
                <input name="unit_id" type="hidden" value="<?=$unit_id?>" />
                <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>部门名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="dep[name]" value="<?=ToolUtil::getSelectType($depInfo,'name','')?>" placeholder="请输入部门名称" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
                <label for="description" class="layui-form-label">
                    <span class="x-red">*</span>部门描述
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="description" name="dep[description]" value="<?=ToolUtil::getSelectType($depInfo,'description','')?>" placeholder="请输入部门描述" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <!--<div class="layui-form-item">
                <label for="father_id" class="layui-form-label">
                    <span class="x-red">*</span>父级id
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="father_id" name="dep[father_id]" required="" value="<?=ToolUtil::getSelectType($depInfo,'father_id','')?>" lay-verify="required" placeholder="请输入站点id"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="unit_id" class="layui-form-label">
                    <span class="x-red">*</span>单位id
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="unit_id" name="dep[unitid]" required="" value="<?=ToolUtil::getSelectType($depInfo,'unit_id','')?>" lay-verify="required" placeholder="请输入站点id"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="siteid" class="layui-form-label">
                    <span class="x-red">*</span>站点id
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="siteid" name="dep[siteid]" required="" value="<?=ToolUtil::getSelectType($depInfo,'siteid','')?>" lay-verify="required" placeholder="请输入站点id"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="app_id" class="layui-form-label">
                    <span class="x-red"></span>子系统id
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="app_id" name="dep[app_id]"  value="<?=ToolUtil::getSelectType($depInfo,'app_id','')?>"  placeholder="子系统id"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>角色</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="auth[1]" lay-skin="primary" title="超级管理员" checked="">
                    <input type="checkbox" name="auth[2]" lay-skin="primary" title="编辑人员">
                    <input type="checkbox" name="auth[3]" lay-skin="primary" title="宣传人员" checked="">
                </div>
            </div>-->
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button  class="layui-btn" lay-filter="add" lay-submit="">
                    提交
                </button>
                <input id="depId" name="depId" value="<?php echo  $depId;  ?>" type="hidden"/>
            </div>
        </form>
    </div>
</div>
<script>

</script>
<?php
$getDepList = \yii\helpers\Url::toRoute(['get-dep-list']);
$update = \yii\helpers\Url::toRoute(['auth/update-dep-status']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$jsStr = <<<STR
    function(value){
       
    }
STR;
//$jsStr = isset($unitInfo['password_hash']) ? $jsStr : "[/(.+){6,12}$/, '密码必须6到12位']";

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