<?php
use common\utils\ToolUtil;
/* @var $this yii\web\View */

$this->title = $title;
$pageSize = \Yii::$app->params['pageSize'];
$limitsJson = \Yii::$app->params['limitsJson'];
?>
<style>
    body{background: #ffffff;}
</style>
<div class="layui-fluid layui-card" style="padding: 30px 0px;">
    <div class="layui-row">
        <form method="post" class="layui-form" action="<?=\yii\helpers\Url::toRoute(['auth/subsystem-edit'])?>">
            <input name="id" type="hidden" value="<?=ToolUtil::getSelectType($model,'id','')?>" />
            <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
            <div class="layui-form-item">
                <label for="real_name" class="layui-form-label">
                    <span class="x-red">*</span>拥有权限
                </label>
                <div class="layui-input-inline">
                    <input name="roles" id="role" type="hidden" value=""  lay-verify="role"/>
                    <div id="authTree" class="demo-tree demo-tree-box" style="border: 1px solid #ddd; padding: 10px 10px;"></div>
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
$getAdminList = \yii\helpers\Url::toRoute(['get-admin-list']);
$update = \yii\helpers\Url::toRoute(['auth/update-admin-status']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$tableJs = <<<JS
    layui.use(['form', 'layer','tree', 'util'],function() {
        $ = layui.jquery;  var form = F(layui.form), layer = layui.layer,tree = layui.tree;
        //自定义验证规则
        form.verify({
             role:function(value){
                c = [];
                var checked = tree.getChecked('authTree'); //获取选中节点的数据
                console.log(checked);
                gc(checked);
                $("#role").val(c);
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
    var c = [];
    layui.use(['tree', 'util'], function() {
        var tree = layui.tree
            , layer = layui.layer
            , util = layui.util;
        var treeData = <?=ToolUtil::getIsset($permissions,"{}")?>;
        //常规用法
        tree.render({
            id:'authTree'
            ,elem: '#authTree' //默认是点击节点可进行收缩
            , data: treeData
            ,showCheckbox: true
        });
    });
    function gc(d){
        for (var i in d){
            c.push(d[i].id);
            if(d[i].children.length > 0){
                gc(d[i].children);
            }
        }
    }
</script>