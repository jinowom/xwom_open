<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = Yii::$app->name;
$this->registerCssFile('@web/css/login.css');
?>
<body class="login-bg">
<div class="login layui-anim layui-anim-up">
    <div class="message"><?=Html::encode($this->title)?></div>
    <div id="darkbannerwrap"></div>
    <form method="post" class="layui-form" action="<?=Url::toRoute(['login'])?>">
        <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
        <input name="username" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" >
        <hr class="hr15">
        <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
        <hr class="hr15">
        <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
        <hr class="hr20" >
    </form>
</div>

<script>
    $(function  () {
        layui.use('form', function(){
            var form = F(layui.form);
            var sFun = function (jsonData) {
                layer.close(load);
                var ic = (jsonData.status == true) ? icon.ICON_OK : icon.ICON_ERROR;
                layer.msg(jsonData.msg,{icon:ic},function () {
                    (jsonData.status == true) ? window.location.href = jsonData.goBack :""
                });
            };
            form.submit('login','',sFun);
            return false;
        });
    })
</script>
<!-- 底部结束 -->
</body>
