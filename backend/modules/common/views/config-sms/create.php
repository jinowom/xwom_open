<div class="panel panel-default">
    <div class="panel-body">
    <form id="form" class="layui-form" style="width:100%;">
       <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>短信提供商</label>
            <div class="layui-input-inline"> 
                <select name="sdk_type" id="sdk_type" lay-search="">
                    <option value="1">阿里</option>
                    <option value="2">腾讯</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>发送类型</label>
            <div class="layui-input-inline"> 
                <select name="send_type" id="send_type" lay-search="">
                    <option value="1">验证码</option>
                    <option value="2">短信通知</option>
                    <option value="3">推广短信</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>AccessKeyID</label>
            <div class="layui-input-block">
                <input type="text" name="access_key_id" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Access Key ID" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>AccessKeySecret</label>
            <div class="layui-input-block">
                <input type="text" name="access_key_secret" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Access Key Secret" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>AccessKeySign</label>
            <div class="layui-input-block">
                <input type="text" name="access_key_sign" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Access Key Sign" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>模板ID</label>
            <div class="layui-input-block">
                <input type="text" name="model_id" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入模板ID" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red"></span>描述</label>
            <div class="layui-input-block">
                <input type="text" name="description" autocomplete="off" class="layui-input" placeholder="请输入描述" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>SendMessage</label>
            <div class="layui-input-block">
                <input type="text" name="send_message" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入发送内容" >
            </div>
        </div>
        <div class="layui-form-item">
            <span class="x-red">*</span>内容的变量函数
            <table class="layui-table">
                <tr id="break">
                    <td><input type="text"  name="key[]" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入变量名" ></td>
                    <td><a type="button" class="layui-btn" id="addLocation"><i class="layui-icon">&#xe624;</i></a></td>
                </tr>
            </table>
        </div>
        <div class="layui-form-item">
            <button type="button" class="layui-btn" id="addbtn" style="float:right;" lay-filter="add" lay-submit="">
                确定
            </button>
        </div>
            <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
        </div>
     </form>
   </div>
</div>
<script>
    //增加访客可访问的区域
    $(document).on('click','#addLocation',function(){
        var str=""
            str+='<tr class="box">'
                str+='<td><input type="text" name="key[]" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入变量名" ></td>'
                str+='<td><a type="button" class="layui-btn layui-btn-danger" id="delLocation"><i class="layui-icon">&#xe67e;</i></a></td>'
            str+='</tr>'
        $('#break').after(str)
    })

    //清除可访问区域
    $(document).on('click','#delLocation',function(){
        $(this).parents('.box').remove()
    })
    /*--------表单部分-------*/
    layui.use(['form'], function(){
        var form = layui.form
           ,layer = layui.layer
        // 提交表单
        form.on('submit(add)', function(data){
            var index = layer.load('添加中',1, {shade: false, offset: '300px'});
            $.post("create",data.field,function(res){
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
                            });
                }
            });

            return false;
        });//监听提交
    });
</script>