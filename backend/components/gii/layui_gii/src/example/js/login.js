layui.use(['form','layer','jquery'],function(){
    var form = layui.form,
        layer = parent.layer === undefined ? layui.layer : top.layer
        $ = layui.jquery;

    //避免退出登录后，子页面 嵌入登录页面的情况
    if(window != top){
        top.location.href = '/sys/site/login'
    }

    //登录按钮
    form.on("submit(login)",function(data){
        var obj = $(this);
        obj.text("登录中...").attr("disabled","disabled").addClass("layui-disabled");
        var data = $('#login-form').serialize();
        $.ajax({
            url: '/sys/site/login',
            type: 'post',
            dataType: 'json',
            data: data,
            success: function (data) {

                obj.text("登录").removeAttr("disabled").removeClass("layui-disabled");

                 if(data.code !== API_CODE.SUCCESS){
                     layer.msg(data.msg, {icon: 5});
                 }else{
                     window.location.href = data.data.url;
                 }
            },
            error: function (e) {
                obj.text("登录").removeAttr("disabled").removeClass("layui-disabled");
                layer.msg('出错了', {icon: 5});
            }
        })
        return false;
    })

    //表单输入效果
    $(".loginBody .input-item").click(function(e){
        e.stopPropagation();
        $(this).addClass("layui-input-focus").find(".layui-input").focus();
    })
    $(".loginBody .layui-form-item .layui-input").focus(function(){
        $(this).parent().addClass("layui-input-focus");
    })
    $(".loginBody .layui-form-item .layui-input").blur(function(){
        $(this).parent().removeClass("layui-input-focus");
        if($(this).val() != ''){
            $(this).parent().addClass("layui-input-active");
        }else{
            $(this).parent().removeClass("layui-input-active");
        }
    })
})
