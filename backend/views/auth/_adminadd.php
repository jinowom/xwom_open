<?php
use common\utils\ToolUtil;
/* @var $this yii\web\View */

$this->title = '添加管理员';
$pageSize = \Yii::$app->params['pageSize'];
$limitsJson = \Yii::$app->params['limitsJson'];
?>
<style>
    body{background: #ffffff;}
</style>
<div class="layui-fluid layui-card" style="padding: 30px 0px;">
    <div class="layui-row">
        <form method="post" class="layui-form" action="<?=\yii\helpers\Url::toRoute(['auth/admin-list'])?>">
            <div class="layui-form-item">
                <input name="user_id" type="hidden" value="<?=ToolUtil::getSelectType($userInfo,'user_id','')?>" />
                <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
                <label for="username" class="layui-form-label">
                    <span class="x-red">*</span>登录名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="username" name="user[username]" value="<?=ToolUtil::getSelectType($userInfo,'username','')?>" placeholder="请输入登录名" required="" lay-verify="required" lay-reqText="请输入登录名"
                           autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>将会成为您唯一的登入名
                </div>
            </div>
            <div class="layui-form-item">
                <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
                <label for="real_name" class="layui-form-label">
                    <span class="x-red">*</span>真实姓名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="real_name" name="user[real_name]" value="<?=ToolUtil::getSelectType($userInfo,'real_name','')?>" placeholder="请输入真实姓名" required="" lay-verify="required" lay-reqText="请输入真实姓名"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="phone" class="layui-form-label">
                    <span class="x-red">*</span>手机
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="phone" name="user[phone]" required="" value="<?=ToolUtil::getSelectType($userInfo,'phone','')?>" lay-verify="phone" placeholder="请输入手机"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_email" class="layui-form-label">
                    <span class="x-red">*</span>邮箱
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="L_email" name="user[email]" required="" value="<?=ToolUtil::getSelectType($userInfo,'email','')?>" lay-verify="email" placeholder="请输入邮箱"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label"><span class="x-red">*</span>角色</label>
                <table class="layui-table layui-input-block" style="width:70%;">
                    <tbody><tr><td>
                        <div>
                            <?php foreach ($roles as $role):?>
                                <input name="user[role_id][]" lay-skin="primary" <?=
                                    (isset($hasRoles) && \yii\helpers\ArrayHelper::isIn($role['name'],$hasRoles)) ? "checked" : ""
                                ?> type="checkbox" title="<?=$role['description']?>" value="<?=$role['name']?>" lay-verify="check">
                            <?php endforeach;?>
                        </div>
                    </td></tr></tbody>
                </table>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>所属单位</label>
                <div class="layui-input-inline">
                    <select name="user[unit]" required="" lay-verify="required" lay-reqText="请选择所属单位">
                        <option value="">请选择所属单位</option>
                        <?php if(isset($unitList)):
                            foreach ($unitList as $key => $unit):?>
                                <option value="<?=$key?>" <?=($unitId == $key) ? "selected" : ""?> ><?=$unit?></option>
                        <?php endforeach;
                            endif;
                        ?>
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="L_pass" class="layui-form-label">
                    <?= (isset($userInfo['password_hash']))? '' : '<span class="x-red">*</span>'?>密码
                </label>
                <div class="layui-input-inline">
                    <input type="password" id="L_pass" name="user[pass]" <?= (isset($userInfo['password_hash']))? "" : "required=''"?> value="" lay-verify="pass" placeholder="请输入密码"
                           autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    6到16个字符 <?= (isset($userInfo['password_hash']))? "<span class='x-red'>(注：更新操作密码不必填，填写则会更新密码！！)</span>" : ""?>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                    <?= (isset($userInfo['password_hash']))? '' : '<span class="x-red">*</span>'?>确认密码
                </label>
                <div class="layui-input-inline">
                    <input type="password" id="L_repass" name="user[repass]" <?= (isset($userInfo['password_hash']))? "" : "required=''"?> lay-verify="repass" placeholder="请再次输入密码"
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
    layui.use(['form', 'layer'],function() {
        $ = layui.jquery;  var form = F(layui.form), layer = layui.layer;
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
            check:function(value){
                var checked = $("input:checkbox:checked").val()
                if(checked == undefined || checked == null || checked == ''){
                    return '请选择角色';
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