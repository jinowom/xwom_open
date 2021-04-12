<div class="panel panel-default">
    <div class="panel-body">
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>变量名</label>
                <div class="layui-input-block">
                     <input type="text" name="name_en" lay-verify="required" autocomplete="off" class="layui-input" placeholder="变量名如：BIANLIANG" >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>显示变量名</label>
                <div class="layui-input-block">
                     <input type="text" name="name" lay-verify="required"  autocomplete="off" placeholder="请输入显示变量名" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>类别</label>
                <div class="layui-input-inline">
                    <select name="type" id="type"  lay-filter="type" lay-search="">
                        <option value=""></option>
                        <option value="1">图文水印</option>
                        <option value="2">文字水印</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item" id="type-1" style="display:none;">
                <div class="layui-inline">
                    <label class="layui-form-label"><span class="x-red">*</span>水印图</label>
                    <div class="layui-input-inline">
                        <button type="button" class="layui-btn" id="upload">
                            <i class="layui-icon">&#xe67c;</i>上传水印图
                        </button>
                        <input type="hidden" name="watermark_url" id="watermark_url" value="" lay-verify="required">
                    </div>
                    <div class="layui-input-inline">
                        <img style="max-width: 50px;max-height:50px;" id="img_url">
                    </div>
                </div>
            </div>
            <div class="layui-form-item" id="type-2" style="display:none;">
               <div class="layui-inline">
                    <label class="layui-form-label"><span class="x-red">*</span>文字内容</label>
                    <div class="layui-input-block">
                        <input type="text" name="watermark_text" id="watermark_text" lay-verify="required"  autocomplete="off" placeholder="请输入文字水印内容" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label"><span class="x-red">*</span>文字大小</label>
                    <div class="layui-input-block">
                        <input type="text" name="text_size" id="text_size" lay-verify="required"  autocomplete="off" placeholder="请输入文字大小" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
               <div class="layui-inline">
                    <label class="layui-form-label"><span class="x-red">*</span>坐标X</label>
                        <div class="layui-input-inline">
                            <input type="text" name="x" lay-verify="required"  autocomplete="off" placeholder="坐标X" class="layui-input"> 
                        </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label"><span class="x-red">*</span>坐标Y</label>
                        <div class="layui-input-inline">
                             <input type="text" name="y" lay-verify="required"  autocomplete="off" placeholder="坐标Y" class="layui-input">
                        </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>操作说明</label>
                <div class="layui-input-block">
                     <input type="text" name="description" lay-verify="required"  autocomplete="off" placeholder="请输入操作说明" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <button type="button" class="layui-btn" id="addbtn" style="float:right;" lay-filter="add" lay-submit="">
                    确定
                </button>
            </div>
            <input name="_csrf" type="hidden" id="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    /*--------表单部分-------*/
    layui.use(['form','upload'], function(){
        var form = layui.form
           ,layer = layui.layer
           ,upload = layui.upload
        // 提交表单
        form.on('submit(add)', function(data){
            var index = layer.load('添加中',1, {shade: false, offset: '300px'});
            $.post("<?=\yii\helpers\Url::toRoute('config-watermark/create') ?>",data.field,function(res){
                if(res.code==200){
                        layer.msg('添加成功', {
                                time: 2000,//3s后自动关闭
                            },function(){
                                layer.close(index);
                                window.parent.location.reload(); //刷新父页面
                            });
                }else{
                    layer.msg('添加失败'+res.message, {
                                time: 2000,//3s后自动关闭
                            },function(){
                                layer.close(index);
                                // window.parent.location.reload(); //刷新父页面
                            });
                }
            });

            return false;
        });//监听提交
        //通过父级模板类型刷出子集模板类型
        form.on('select(type)', function(data){//监听接待部门选择框
            if(data.value == 1){
                $('#watermark_text').attr('lay-verify',"");
                $('#text_size').attr('lay-verify',"");
                $('#watermark_url').attr('lay-verify','required');
                $("#type-1").show();
                $("#type-2").hide();
            }else if(data.value == 2){
                $("#type-2").show();
                $("#type-1").hide();
                $('#watermark_text').attr('lay-verify',"required");
                $('#text_size').attr('lay-verify',"required");
                $('#watermark_url').attr('lay-verify','');
            }else{
                $("#type-1").hide();
                $("#type-2").hide();
            }
        });
        // 轮播图上传
        var upload = upload.render({
            elem: '#upload'
            ,url: "<?=\yii\helpers\Url::toRoute('config-watermark/upload-do') ?>"
            ,acceptMime: 'image/*'
            ,exts: "jpg|png"
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#img_url').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                $('#watermark_url').attr('value',res.img)
            }
        });
    })

</script>