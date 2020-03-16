<?php
use common\utils\ToolUtil;
/* @var $this yii\web\View */

$this->title = '个人信息';
?>
<style>
    body{background: #ffffff;}
</style>
<div class="layui-fluid layui-card" style="padding: 30px 0px;">
    <div class="layui-row">
        <form method="post" class="layui-form" action="<?=\yii\helpers\Url::toRoute(['index/update-self-data'])?>">
            <div class="layui-form-item">
                <input name="user_id" type="hidden" value="<?=ToolUtil::getSelectType($userInfo,'user_id','')?>" />
                <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
                <label for="username" class="layui-form-label">
                    登录名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="username" disabled name="user[username]" value="<?=ToolUtil::getSelectType($userInfo,'username','')?>" placeholder="请输入登录名" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <input name="_csrfBackend" type="hidden" value="<?=\Yii::$app->request->csrfToken?>" />
                <label for="real_name" class="layui-form-label">
                    真实姓名
                </label>
                <div class="layui-input-inline">
                    <input type="text" disabled id="real_name" name="user[real_name]" value="<?=ToolUtil::getSelectType($userInfo,'real_name','')?>" placeholder="请输入登录名" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="phone" class="layui-form-label">
                    <span class="x-red">*</span>绑定手机
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="phone" readonly name="phone" value="<?=ToolUtil::getSelectType($userInfo,'phone','')?>" placeholder="请输入手机"
                           autocomplete="off" class="layui-input">
                </div>
                <div class="layui-input-block">
                    <button type="button" id="updatePhone" data-m="s" class="layui-btn layui-btn-warm">修改绑定手机</button>
                </div>
            </div>
            <div class="layui-form-item" id="formCode" style="display: none;">
                <label for="phone" class="layui-form-label">
                    新绑定手机
                </label>
                <div class="layui-input-inline">
                    <input type="number" id="newPhone" name="user[phone]" required="" value="" lay-verify="" placeholder="请输入新的手机号码"
                           autocomplete="off" class="layui-input">
                </div>
                <div class="layui-input-inline">
                    <div class="layui-input-inline" style="width: 120px; margin-right: 0px;">
                    <input type="number" id="code" name="user[code]"  required="" value="" lay-verify="" placeholder="请输入验证码"
                           autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-input-inline" style="width: 50px;">
                        <button type="button" id="getCode" class="layui-btn layui-btn-normal layui-btn-sm">获取验证码</button>
                    </div>
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
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button  class="layui-btn" lay-filter="add" lay-submit="">
                    确认修改
                </button>
            </div>
        </form>
    </div>
</div>
<script>

</script>
<?php
$sendSms = \yii\helpers\Url::toRoute(['index/send-sms']);
$update = \yii\helpers\Url::toRoute(['auth/update-admin-status']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$serverTime = ToolUtil::getDate(time(),"Y-m-d H:i:s");
$endTime = ToolUtil::getDate(strtotime("+30 seconds"),"Y-m-d H:i:s");
$tableJs = <<<JS
    $("#updatePhone").on('click',function(){
        var m = $(this).data('m');
        var text = (m == 's') ? "取消修改" : "修改绑定手机";
        (m == 's') ? $(this).data('m','x') : $(this).data('m','s'); 
        (m == 's') ? $("#formCode").show() : $("#formCode").hide(); 
        if(m == 's'){
            $("#newPhone").val(null).attr("lay-verify",'phone'); 
            $("#code").val(null).attr("lay-verify",'code');
        }else{
            $("#newPhone").val(null).attr("lay-verify",''); 
            $("#code").val(null).attr("lay-verify",'');
        } 
        $(this).text(text);
    });
    layui.use(['form', 'layer', 'util', 'laydate'],function() {
        $ = layui.jquery;  var form = F(layui.form), layer = layui.layer,util = layui.util,laydate = layui.laydate;
        //倒计时
        function timeR(){
            var i = false;
            var thisTimer, setCountdown = function(serverTime,endTime){
              i = true;
              $("#getCode").prop("disabled",true);
              var endTime = new Date(endTime) //结束日期
              ,serverTime = new Date(serverTime);
              clearTimeout(thisTimer);
              util.countdown(endTime, serverTime, function(date, serverTime, timer){
                var str = '请在' + date[3] + '秒后重试';
                if(date[3] == 0){
                    str = '获取验证码';
                    $("#getCode").prop("disabled",false);
                }
                lay('#getCode').html(str);
                thisTimer = timer;
              });
            };
            if(i == false){
                setCountdown("{$serverTime}","{$endTime}");            
            }
        }        
        $("#getCode").on('click',function(){
            var phone = $("#newPhone").val();
            var load;
            Cajax({
                type:"post",
                url:"{$sendSms}",
                data:{_csrfBackend:"{$_csrfBackend}",phone:phone},
            },function() {
                load = layer.load();
            },function(Json){
                layer.close(load);
                var ic = (Json.status == true) ? icon.ICON_OK : icon.ICON_ERROR;
                layer.msg(Json.msg,{icon:ic});
                if(Json.status === true){
                    timeR();
                }
            });
        })
        

        
        //自定义验证规则
        form.verify({
            nikename: function(value) {
                if (value.length < 5) {
                    return '昵称至少得5个字符啊';
                }
            },
            code: function(value){
                if(value.length < 1){
                    return '验证码不能为空';
                }
            }
        });
        //监听提交
        var sFun = function (jsonData) {
                layer.close(load);
                var ic = (jsonData.status == true) ? icon.ICON_OK : icon.ICON_ERROR;
                layer.msg(jsonData.msg,{icon:ic},function () {
                    if(jsonData.status == true){
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