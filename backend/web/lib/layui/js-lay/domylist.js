/**
 * Created by WANGWEIHUA on 2020/3/28.
 */
layui.define(['form','layer','upload'],function(exports){ //提示：模块也可以依赖其它模块，如：layui.define('layer', callback);
    $ = layui.jquery;  var form = F(layui.form), layer = layui.layer, upload = layui.upload, uploadUrl, pathFile = [];
    var FF = {
        init:function(uEle,uEleIns,bEle,bTable){ //uEle 选择文件,uEleIns 文件列表,bEle 上传文件
            uploadUrl = $(uEle).data('upload-url');
            this.formFun();
            this.uploadFun(uEle,uEleIns,bEle);
            this.initBindEvent(bTable);
        },
        formFun: function(){

            layui.form.on('select(xpaper)',function(data){
                var url = $(data.elem).data('url');
                if(data.value.length > 0){
                    Cajax({ type:'get', url:url, data: { pId : data.value } },function(){
                        load = layer.load();
                    },function(res){
                        layer.closeAll();
                        var html = '<option value="">选择版面</option>';
                        if(res.status == true){
                            for (var i in res.msg){
                                html+='<option value="'+i+'">'+res.msg[i]+'</option>';
                            }
                        }
                        $("#page").html(html);
                        layui.form.render('select');
                    });
                }
            });
            //自定义验证规则
            form.verify({
            });
            //监听提交
            var sFun = function (jsonData) {
                layer.close(load);
                var ic = (jsonData.status == true) ? icon.ICON_OK : icon.ICON_ERROR;
                layer.msg(jsonData.msg,{icon:ic},function () {
                    if(jsonData.status == true){
                        // xadmin.father_reload();
                        window.parent.layui.mylist.render('myTable');
                        xadmin.close();
                    }
                });
            };
            form.submit('add','',sFun);

        },
        uploadFun: function(uEle,uEleIns,bEle){
            var tindex,that = this,uploadListIns = $(uEleIns),uploadList = upload.render({
                elem: uEle, url: uploadUrl, accept: 'file', multiple: true, auto: false, bindAction: bEle,data:{
                    fileName: function(){
                        return $("#file_name").val();
                    },
                    fileCaption: function(){
                        return $("#file_caption").val();
                    }
                },
                choose: function(obj){ //文件队列
                    var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
                    //读取本地文件
                    obj.preview(function(index, file, result){
                        tindex = index;
                        var tr = $(['<tr id="upload-'+ index +'">'
                            ,'<td style="text-align: center" width="20%"><input class="layui-input" placeholder="请输入附件名称" type="text" id="'+index+'-file_name" name="file_name" value="'+ file.name +'"></td>'
                            ,'<td style="text-align: center" width="20%">'+ (file.size/1024).toFixed(1) +'kb</td>'
                            ,'<td style="text-align: center" width="20%">等待上传</td>'
                            ,'<td style="text-align: center" width="20%">'
                            ,'<button type="button" class="layui-btn layui-btn-xs upload-reload layui-hide">重传</button>'
                            ,'<button class="layui-btn layui-btn-xs layui-btn-danger upload-delete">删除</button>'
                            ,'</td>'
                            ,'</tr>'].join(''));
                        //单个重传
                        tr.find('.upload-reload').on('click', function(){
                            obj.upload(index, file);
                        });
                        //删除
                        tr.find('.upload-delete').on('click', function(){
                            delete files[index]; //删除对应的文件
                            tr.remove();
                            uploadList.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                        });
                        uploadListIns.append(tr);

                        $("#"+index+'-file_name').blur(function(){
                            var val = $(this).val();
                            obj.resetFile(index,file, val);
                        })
                    });
                },

                done: function(res, index, upload){
                    if(res.status){ //上传成功
                        that.uploadVal(res.filePath,res.status);
                        var tr = uploadListIns.find('tr#upload-'+ index)
                            ,tds = tr.children();
                        $("#"+index+"-file_name","#"+index+"-file_caption").prop('disabled',true);
                        tds.eq(2).html('<span style="color: #5FB878;">上传成功</span>');
                        tds.eq(3).html(''); //清空操作
                        return delete this.files[index]; //删除文件队列已经上传成功的文件
                    }
                    this.error(index, upload);
                },
                error: function(index, upload){
                    var tr = uploadListIns.find('tr#upload-'+ index)
                        ,tds = tr.children();
                    tds.eq(2).html('<span style="color: #FF5722;">上传失败</span>');
                    tds.eq(3).find('.upload-reload').removeClass('layui-hide'); //显示重传
                }
            });
        },
        uploadVal: function(p,i){
            (i == true) ? pathFile.push(p) : pathFile.pop(p);
            $("#uplodFiles").val(pathFile);
        },
        initBindEvent: function(bTable){
            var bt = $(bTable);
            //删除
            bt.find('.upload-delete').on('click', function(){
                var url = $(this).data('do'),i = $(this).data('index');
                layer.confirm('真的删除吗？', function(index){
                        Cajax({
                            url:url,type:'get',data:{fid:i,event:'del'}
                        },function(){
                            load = layer.load();
                        },function(JsonData){
                            layer.closeAll();
                            layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
                            if(JsonData.status){
                                $("#uploadL-"+i).remove();
                            }
                        })
                    }
                );
            });
            bt.find('.upload-select').on('click',function(){
                //查看
                var src = $(this).data('src');
                window.open(src);
            });
            bt.find('.upload-update').on('click',function(){
                //修改
                var i = $(this).data('index'),text = $(this).text(),url=$(this).data('do');
                if(text == "保存"){
                    $(this).text("修改");
                    var fileN = $("#u"+i+"-file_name").attr('disabled',true).val();
                    var fileC = $("#u"+i+"-file_caption").attr('disabled',true).val();
                    Cajax({
                        url:url,type:'get',data:{fid:i, fileN: fileN, fileC: fileC}
                    },function(){
                        load = layer.load();
                    },function(JsonData){
                        layer.closeAll();
                        layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
                    })
                }else{
                    $(this).text("保存");
                    $("#u"+i+"-file_name").removeAttr('disabled').focus();
                    $("#u"+i+"-file_caption").removeAttr('disabled');
                }
            })
        }
    }
    exports('domylist', FF);
});