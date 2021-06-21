/*!
 * Cropper v3.0.0
 */

layui.config({
    base: '/lib/cropper/' //layui自定义layui组件目录
}).define(['form','jquery','layer','cropper'],function (exports) {
    var $ = layui.jquery
        ,form = layui.form
        ,layer = layui.layer;
    var html_1 = "<link rel=\"stylesheet\" href=\"/lib/cropper/cropper.css\">\n" +
        "<div class=\"layui-fluid showImgEdit\" style=\"display: none\">\n" +"</div>"
    var html_2 = "    <div class=\"layui-form-item\">\n" +
        "        <div class=\"layui-input-inline layui-btn-container\" style=\"width: auto;\">\n" +
        "            <label for=\"cropper_avatarImgUpload\" class=\"layui-btn layui-btn-primary\">\n" +
        "                <i class=\"layui-icon\">&#xe67c;</i>选择图片\n" +
        "            </label>\n" +
        "            <input class=\"layui-upload-file\" id=\"cropper_avatarImgUpload\" type=\"file\" value=\"选择图片\" name=\"file\">\n" +
        "             <form class=\"layui-form\">\n" +
        "               图片比例: <div class=\"layui-input-inline\">\n"+
        "                           <select id=\"imageBiLi\" lay-verify=\"imageBiLi\" lay-filter=\"imageBiLi\">\n"+
        "                                <option value=\"\"></option>\n"+
        "                                <option value=\"3\">1:1(多用于图标)</option>\n"+
        "                                <option value=\"1\">4:3(多用于小图)</option>\n"+
        "                                <option value=\"2\">16:9(多用于横图)</option>\n"+
        "                                <option value=\"4\">9:16(多用于微视频)</option>\n"+
        "                           </select>\n" +
        "                     </div>\n" +
        "             </form>" +
        "        </div>\n" +
        "    </div>\n" +
        "    <div class=\"layui-row layui-col-space15\">\n" +
        "        <div class=\"layui-col-xs9\">\n" +
        "            <div class=\"readyimg\" style=\"height:450px;background-color: rgb(247, 247, 247);\">\n" +
        "                <img src=\"\" >\n" +
        "            </div>\n" +
        "        </div>\n" +
        "        <div class=\"layui-col-xs3\">\n" +
        "            <div class=\"img-preview\" style=\"width:200px;height:200px;overflow:hidden\">\n" +
        "            </div>\n" +
        "        </div>\n" +
        "    </div>\n" +
        "    <div class=\"layui-row layui-col-space15\">\n" +
        "        <div class=\"layui-col-xs9\">\n" +
        "            <div class=\"layui-row\">\n" +
        "                <div class=\"layui-col-xs6\">\n" +
        "                    <button type=\"button\" class=\"layui-btn layui-icon layui-icon-left\" cropper-event=\"rotate\" data-option=\"-15\" title=\"Rotate -90 degrees\"> 向左旋转</button>\n" +
        "                    <button type=\"button\" class=\"layui-btn layui-icon layui-icon-right\" cropper-event=\"rotate\" data-option=\"15\" title=\"Rotate 90 degrees\"> 向右旋转</button>\n" +
        "                </div>\n" +
        "                <div class=\"layui-col-xs5\" style=\"text-align: right;\">\n" +
        "                    <button type=\"button\" class=\"layui-btn layui-icon layui-icon-refresh\" cropper-event=\"reset\" title=\"重置图片\"></button>\n" +
        "                </div>\n" +
        "            </div>\n" +
        "        </div>\n" +
        "        <div class=\"layui-col-xs3\">\n" +
        "            <button class=\"layui-btn layui-btn-fluid\" cropper-event=\"confirmSave\" type=\"button\"> 保存修改</button>\n" +
        "        </div>\n" +
        "    </div>\n" +
        "\n" +
        "</div>";
    //通过频道检索出频道下的栏目和专题
    form.on('select(imageBiLi)', function(data){//监听接待部门选择框
        if(data.value == 1){
            var image = $(".showImgEdit .readyimg img")
            ,src = image.attr('src')
            ,preview = '.showImgEdit .img-preview'
            ,options = {aspectRatio: 4/3, preview: preview, viewMode:1};
            image.cropper('destroy').attr('src', src).cropper(options)
        }else if(data.value == 2){
            var image = $(".showImgEdit .readyimg img")
            ,src = image.attr('src')
            ,preview = '.showImgEdit .img-preview'
            ,options = {aspectRatio: 16/9, preview: preview, viewMode:1};
            image.cropper('destroy').attr('src', src).cropper(options)
        }else if(data.value == 3){
            var image = $(".showImgEdit .readyimg img")
            ,src = image.attr('src')
            ,preview = '.showImgEdit .img-preview'
            ,options = {aspectRatio: 1/1, preview: preview, viewMode:1};
            image.cropper('destroy').attr('src', src).cropper(options)
        }else if(data.value == 4){
            var image = $(".showImgEdit .readyimg img")
            ,src = image.attr('src')
            ,preview = '.showImgEdit .img-preview'
            ,options = {aspectRatio: 9/16, preview: preview, viewMode:1};
            image.cropper('destroy').attr('src', src).cropper(options)
        }
    })
    var obj = {
        render: function(e){
            if($('.showImgEdit').length == 0){
                $('body').append(html_1);
            }
            var self = this,
                elem = e.elem,
                saveW = e.saveW,
                saveH = e.saveH,
                mark = e.mark,
                area = e.area,
                url = e.url,
                done = e.done;

            $(elem).on('click',function () {
                var oldUrl = $(this).nextAll('input').val()
                $('.showImgEdit').html(html_2);
                form.render();
                var content = $('.showImgEdit')
                ,image = $(".showImgEdit .readyimg img")
                ,preview = '.showImgEdit .img-preview'
                ,file = $(".showImgEdit input[name='file']")
                ,options = {aspectRatio: mark,preview: preview,viewMode:1};
                if(oldUrl.length != 0){
                    image.cropper('destroy').attr('src', '/'+oldUrl).cropper(options)
                }
                layer.open({
                    type: 1
                    ,title:'上传剪切图片'
                    , content: content
                    , area: area
                    , success: function () {
                        image.cropper(options);
                    }
                    , cancel: function (index) {
                        layer.close(index);
                        image.cropper('destroy');
                    }
                });
                $(".layui-btn").on('click',function () {
                    var event = $(this).attr("cropper-event");
                    //监听确认保存图像
                    if(event === 'confirmSave'){
                        image.cropper("getCroppedCanvas",{
                            width: saveW,
                            height: saveH
                        }).toBlob(function(blob){
                            var formData=new FormData();
                            formData.append('file',blob,'head.jpg');
                            layer.msg('上传中...', {icon: 16, shade: 0.01, time: 0 })
                            $.ajax({
                                method:"post",
                                url: url, //用于文件上传的服务器端请求地址
                                data: formData,
                                processData: false,
                                contentType: false,
                                success:function(result){
                                    layer.close(layer.msg());
                                    if(result.code == 200){
                                        layer.msg('成功');
                                        layer.closeAll('page');
                                        return done(result.img);
                                    }else{
                                        layer.alert(result.msg,{icon: 2});
                                    }
    
                                }
                            });
                        });
                        //监听旋转
                    }else if(event === 'rotate'){
                        var option = $(this).attr('data-option');
                        image.cropper('rotate', option);
                        //重设图片
                    }else if(event === 'reset'){
                        image.cropper('reset');
                    }
                    //文件选择
                    file.change(function () {
                        var r= new FileReader();
                        var f=this.files[0];
                        r.readAsDataURL(f);
                        r.onload=function (e) {
                            image.cropper('destroy').attr('src', this.result).cropper(options);
                        };
                    });
                });
            });
        }

    };
    exports('croppers', obj);
});