<?php
use common\utils\ToolUtil;
/* @var $this yii\web\View */

$this->title = '添加角色';
$pageSize = \Yii::$app->params['pageSize'];
$limitsJson = \Yii::$app->params['limitsJson'];
?>
<style>
    body{background: #ffffff;}
</style>
<div class="layui-fluid">
    <div class="layui-row">
        <form action="<?=\yii\helpers\Url::toRoute(['auth/auth-list'])?>" method="post" class="layui-form layui-form-pane">
            <input name="name" type="hidden" value="<?=ToolUtil::getSelectType($model,'name','')?>" />
            <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>角色名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="description" name="auth[description]" required="" value="<?=ToolUtil::getSelectType($model,'description','')?>" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>角色标识
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="auth[name]" required="" <?=isset($model['name'])?"disabled":""?> lay-verify="required" value="<?=ToolUtil::getSelectType($model,'name','')?>"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-inline">
                    <input type="text" id="order_sort" name="auth[order_sort]" value="<?=ToolUtil::getSelectType($model,'order_sort','100')?>" placeholder="请输入排序"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">
                    拥有权限
                </label>
                <input name="auth[roles]" id="role" type="hidden" value=""  lay-verify="role"/>
                <div id="authTree" class="demo-tree demo-tree-box" style="border: 1px solid #ddd; padding: 10px 10px;"></div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" lay-submit="" lay-filter="add">增加</button>
            </div>
        </form>
    </div>
</div>
<?php
$getAdminList = \yii\helpers\Url::toRoute(['get-admin-list']);
$update = \yii\helpers\Url::toRoute(['auth/update-admin-status']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$jsStr = <<<STR
    function(value){
        var patt1 = new RegExp(/(.+){6,12}$/);
        if(value != '' && (patt1.test(value) == false)){
            // return [/(.+){6,12}$/, '密码必须6到12位'];                    
            return '密码必须6到12位';                    
        }
    }
STR;
$jsStr = isset($userInfo['password_hash']) ? $jsStr : "[/(.+){6,12}$/, '密码必须6到12位']";

$tableJs = <<<JS
    layui.use(['tree','form', 'layer'],function() {
        $ = layui.jquery;  var form = F(layui.form), layer = layui.layer, _form = layui.form,tree = layui.tree;
        //自定义验证规则
        form.verify({
            nikename: function(value) {
                if (value.length < 5) {
                    return '昵称至少得5个字符啊';
                }
            },
            pass: $jsStr,
            repass: function(value) {
                if ($('#L_pass').val() != $('#L_repass').val()) {
                    return '两次密码不一致';
                }
            },
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
        console.log(treeData);
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