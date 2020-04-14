<?php
use common\utils\ToolUtil;
/* @var $this yii\web\View */

$this->title = '单位编辑';
$pageSize = \Yii::$app->params['pageSize'];
$limitsJson = \Yii::$app->params['limitsJson'];
?>
<style>
    body{background: #ffffff;}
</style>
<div class="layui-fluid" style="padding: 30px 0px;">
    <div class="layui-row">
        <form method="post" class="layui-form" action="<?=\yii\helpers\Url::toRoute(['notice/notice-edit'])?>">
            <div class="layui-form-item">
                <input name="notice[noticeid]" type="hidden" value="<?=ToolUtil::getSelectType($noticeInfo,'noticeid','')?>" />
                <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>公告标题
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="title" name="notice[title]" value="<?=ToolUtil::getSelectType($noticeInfo,'title','')?>" placeholder="请输入公告标题" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
                <label for="description" class="layui-form-label">
                    <span class="x-red">*</span>公告内容
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="content" name="notice[content]" value="<?=ToolUtil::getSelectType($noticeInfo,'content','')?>" placeholder="请输入通知内容" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button  class="layui-btn" lay-filter="add" lay-submit="">
                    修改
                </button>
            </div>
        </form>
    </div>
</div>
<script>

</script>
<?php
$getNoticeList = \yii\helpers\Url::toRoute(['get-notice-list']);
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