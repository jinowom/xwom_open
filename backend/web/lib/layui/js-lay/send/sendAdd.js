/**
 * Created by WANGWEIHUA on 2020/3/28.
 */
layui.define(['layer','form','upload','table'],function(exports){ //提示：模块也可以依赖其它模块，如：layui.define('layer', callback);
    var $ = layui.jquery,form = F(layui.form), layer = layui.layer, upload = layui.upload, uploadUrl = $("#uploadUrl").val(), csrf = $("#csrf").val(), table = layui.table, selectArr = new Array;
    //自定义验证规则
    var FF = {
        init:function(){
            page = $("#myTable").data('page');pages = $("#myTable").data('pages');
            form.verify({
                image: function(value,item){
                    if(value.length <= 0){
                        return "请上传图片！";
                    }
                },
            });
            form.submit('add','',this.sFun);
            this.initUpload("#uploadBg","#uploadBgI","#uploadBgText");
            this.render("myTable");
            this.toolTable("myTable");
            $(document).on('change','input.fileInput',function (obj) {
                var file = $(this)[0].files[0], id = $(this).data('id');
                var imgSrc;
                if (!/image\/\w+/.test(file.type)) {
                    layer.msg('请上传图片！', {icon: icon.ICON_FAIL});
                    return false;
                }
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function() {
                    imgSrc = this.result;
                    var fd = new FormData();
                    fd.append("file",file);
                    $.ajax({
                        url: uploadUrl,
                        type: "POST",
                        processData: false,
                        contentType: false,
                        data: fd,
                        success: function(d) {
                            if(d.status == true){
                                $("#input_"+id).val(d.msg);
                                $('#img_'+id).attr("src", d.msg);
                            }else{
                                layer.msg('上传失败！', {icon: icon.ICON_FAIL});
                            }
                        },error:function(d){
                            layer.msg('上传失败！请重新上传', {icon: icon.ICON_FAIL});
                            return false;
                        }
                    })
                };
            })
            $(document).on('click','img.fileImg',function () {
                $(this).parent().find("input.fileInput").click();
            })
        },
        //监听行事件
        toolTable:function(ele){
            var _this = this;
            table.on('tool('+ele+')',function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    _this.rowDel(obj,data);
                } else if(obj.event === 'edit'){
                    layer.confirm('确定要选择该稿件吗？', function(index){
                        if($.inArray(obj.data.id,selectArr) != -1){
                            layer.msg("已经存在请重新选择",{icon: icon.ICON_ERROR});
                            return false;
                        }
                        if(selectArr.length >= 5){
                            layer.msg("最多只能选择5个稿件！",{icon: icon.ICON_ERROR});
                            return false;
                        }
                        selectArr.push(obj.data.id);
                        var tr = $(['<tr class="files" id="upload-'+ index +'">'
                            ,'<td style="text-align: center">'+obj.data.title+'</td>'
                            //<input type="file" onchange="image_change(this)" :id="'input_change_'+index"  datatype="*" nullmsg="请选择图片" hidden /><br/>
                            ,'<td style="text-align: center">'
                            ,'<input id="input_'+obj.data.id+'" type="hidden" name="img[]"  />'
                            ,'<input data-id="'+obj.data.id+'" type="file" class="fileInput" datatype="*" nullmsg="请选择图片" style="display: none" />'
                            ,'<img id="img_'+obj.data.id+'" src="images/def.jpg" class="fileImg"  onclick="" height="50"></td>'
                            ,'<td style="text-align: center">'
                            ,'<button class="layui-btn layui-btn-xs demo-reload layui-hide">重传</button>'
                            ,'<button data-id="'+obj.data.id+'" class="layui-btn layui-btn-xs layui-btn-danger demo-delete">删除</button>'
                            ,'</td>'
                            ,'</tr>'].join(''));
                        tr.find('.demo-delete').on('click', function(index){
                            layer.confirm('确定要删除吗？', function(index){
                                var id = $(this).data('id');
                                tr.remove();
                                var index = selectArr.indexOf(id);
                                selectArr.splice(index,1);
                                layer.closeAll();
                            },function (index) {

                            });
                        });
                        layer.closeAll();
                        $("#demoList").append(tr);
                    },function(index){

                    })
                }
            });
        },
        render:function(ele){
            layer.load(2);
            table.render({
                id:ele,
                elem:'#'+ele,
                toolbar:"#barTool",
                url: $("#"+ele).attr('lay-url'), //数据接口
                method:"POST", //
                where:{_csrfBackend:csrf},
                page: true, //开启分页
                limit: page,
                height: '400px',
                limits: pages,
                loading:true,
                cols: [[
                    {field: 'title',align:'center', title: '稿件标题', minWidth:280, sort: false, },
                    {field: 'author',align:'center', title: '稿件作者', minWidth:100, width:100, sort: false},
                    {field: 'type_name',align:'center', title: '稿件类型', minWidth:100,width:100,sort: false},
                    {field: 'status_name',align:'center', title: '稿件状态', minWidth:100,width:100,sort: false},
                    {field: 't_date',align:'center', title: '投稿时间', minWidth:150,width:150,sort: false},
                    {fixed: 'right',align:'center', title:'操作', toolbar: '#tdTool', width:100}
                ]],done: function () {
                    layer.closeAll('loading');
                }
            });
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
    exports('sendAdd', FF);
});