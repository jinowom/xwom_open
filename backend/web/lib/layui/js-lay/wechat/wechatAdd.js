/**
 * Created by WANGWEIHUA on 2020/3/28.
 */
layui.define(['layer','form','upload'],function(exports){ //提示：模块也可以依赖其它模块，如：layui.define('layer', callback);
    var $ = layui.jquery,form = F(layui.form), layer = layui.layer, upload = layui.upload, uploadUrl = $("#uploadUrl").val(), csrf = $("#csrf").val();
    //自定义验证规则
    var FF = {
        init:function(){
            form.verify({
                image: function(value,item){
                    if(value.length <= 0){
                        return "请上传图片！";
                    }
                },
            });
            form.submit('add','',this.sFun);
            this.initUpload("#uploadBg2","#uploadBgI2","#uploadBgText2");
            this.initUpload("#uploadBg","#uploadBgI","#uploadBgText");
        },
        sFun:function(jsonData){
            layer.close(load);
            var ic = (jsonData.status == true) ? icon.ICON_OK : icon.ICON_ERROR;
            layer.msg(jsonData.msg,{icon:ic},function () {
                if(jsonData.status == true){
                    window.parent.layui.wechat.render('myTable');
                    xadmin.close();
                }
            });
        },
        initUpload:function(elem,eleI,eleT){
            //普通图片上传
            var uploadInst = upload.render({
                elem: elem
                ,url: uploadUrl //改成您自己的上传接口
                ,data:{_csrfBackend:csrf}
                ,before: function(obj){
                    //预读本地文件示例，不支持ie8
                    obj.preview(function(index, file, result){
                        $(eleI).attr('src', result); //图片链接（base64）
                    });
                }
                ,done: function(res){
                    //如果上传失败
                    if(res.status==false){
                        return layer.msg(res.msg);
                    }else{
                        $(eleT).text(null);
                        $(eleI).parent().find('input').val(res.msg);
                    }
                    //上传成功
                }
                ,error: function(index, upload){
                    //演示失败状态，并实现重传
                    var demoText = $(eleT);
                    demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                    demoText.find('.demo-reload').on('click', function(){
                        uploadInst.upload();
                    });
                }
            });
        }
    };
    exports('wechatAdd', FF);
});