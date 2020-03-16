<?php
use common\utils\ToolUtil;
/* @var $this yii\web\View */

$this->title = '添加菜单';
$pageSize = \Yii::$app->params['pageSize'];
$limitsJson = \Yii::$app->params['limitsJson'];
?>
<style>
    body{background: #ffffff;}
</style>
<div class="layui-fluid layui-card" style="padding: 30px 0px;">
    <div class="layui-row">
        <form method="post" class="layui-form" action="<?=\yii\helpers\Url::toRoute(['auth/menu-list'])?>">
            <div class="layui-form-item">
                <input name="name" type="hidden" value="<?=ToolUtil::getSelectType($model,'name','')?>" />
                <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
                <label for="username" class="layui-form-label">
                    <span class="x-red">*</span>父菜单名称
                </label>
                <div class="layui-input-inline">
                    <input id="parent_name" name="auth[parent_name]" type="hidden" value="<?=ToolUtil::getSelectType($pModel,'name','')?>" />
                    <input type="text" id="parent_description" readonly name="parent_description" value="<?=ToolUtil::getSelectType($pModel,'description','')?>" placeholder="请输入父菜单名称" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="real_name" class="layui-form-label">
                    <span class="x-red">*</span>菜单名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="description" name="auth[description]" value="<?=ToolUtil::getSelectType($model,'description','')?>" placeholder="请输入菜单名称" required="" lay-verify="required|name"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="real_name" class="layui-form-label">
                    <span class="x-red">*</span>菜单标识
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="auth[name]" <?=isset($model['name'])?"disabled":""?> value="<?=ToolUtil::getSelectType($model,'name','')?>" placeholder="请输入菜单标识" required="" lay-verify="required|name"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">是否是菜单</label>
                <div class="layui-input-block">
                    <input type="radio" name="auth[is_menu]" value="1" title="是" <?php
                        if(isset($model['is_menu'])){
                            echo ($model['is_menu'] == 1) ? "checked" : "";
                        }else{
                            echo "checked";
                        }
                    ?>/>
                    <input type="radio" name="auth[is_menu]" value="0" title="否" <?=(isset($model['is_menu']) && $model['is_menu'] == 0)? "checked" : ""?>/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-inline">
                    <input type="text" id="order_sort" name="auth[order_sort]" value="<?=ToolUtil::getSelectType($model,'order_sort','100')?>" placeholder="请输入排序"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">图标</label>
                <div class="layui-input-inline">
                    <input type="text" id="icon" name="auth[icon]" value="<?=ToolUtil::getSelectType($model,'icon','layui-icon-left')?>" placeholder="请输入图标" lay-verify="icon"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button  class="layui-btn" lay-filter="add" lay-submit="">
                    增加
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
    layui.config({
        base: 'lib/layui/lay/modules/'
    }).use(['form', 'layer','iconPicker'],function() {
        $ = layui.jquery,iconPicker=layui.iconPicker;  var form = F(layui.form), layer = layui.layer;
        //自定义验证规则
        form.verify({
            name:function(value) {
                if (value.length > 64) {
                    return '菜单标识最多包含64个字符';
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
        
        
        iconPicker.render({
            // 选择器，推荐使用input
            elem: '#icon',
            // 数据类型：fontClass/unicode，推荐使用fontClass
            type: 'fontClass',
            // 是否开启搜索：true/false
            search: false,
            // 是否开启分页
            page: false,
            // 每页显示数量，默认12
            limit: 12,
            cellWidth: '40px',
            // 点击回调
            click: function (data) {
                console.log(data);
            },
            // 渲染成功后的回调
            success: function(d) {
                console.log(d);
            }
        });
    });
JS;
$this->registerJs($tableJs,\yii\web\View::POS_END);
?>
<script>
</script>