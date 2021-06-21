<?php /* @var $model common\models\config\ConfigSms */ ?>

<div class="panel panel-default">
    <div class="panel-body">
    <form id="form" class="layui-form" style="width:100%;">
         <input type="hidden" name="id" value="<?=$model->id ?>" >
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>短信提供商</label>
            <div class="layui-input-block">
                <input type="text" name="sdk_com" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入短信提供商" value="<?=$model->sdk_com ?>" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>AccessKeyID</label>
            <div class="layui-input-block">
                <input type="text" name="access_key_id" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Access Key ID" value="<?=$model->access_key_id ?>" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>AccessKeySecret</label>
            <div class="layui-input-block">
                <input type="text" name="access_key_secret" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Access Key Secret" value="<?=$model->access_key_secret ?>" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>AccessKeySign</label>
            <div class="layui-input-block">
                <input type="text" name="access_key_sign" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Access Key Sign" value="<?=$model->access_key_sign ?>" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>模板ID</label>
            <div class="layui-input-block">
                <input type="text" name="model_id" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入模板ID"  value="<?=$model->model_id ?>" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red"></span>描述</label>
            <div class="layui-input-block">
                <input type="text" name="description" autocomplete="off" class="layui-input" placeholder="请输入描述" value="<?=$model->description ?>" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>SendMessage</label>
            <div class="layui-input-block">
                <input type="text" name="send_message" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入发送内容" value="<?=$model->send_message ?>" >
            </div>
        </div>
        <div class="layui-form-item">
            <span class="x-red">*</span>内容的变量函数
            <table class="layui-table">
                <?php if(!empty($model->sendvariable)){ ?>
                    <?php foreach ($model->sendvariable as $key => $value){ ?>
                        <?php if($key == 0){ ?>
                            <tr id="break">
                                <td><input type="text"  name="key[]" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入变量名" value="<?=$value ?>" ></td>
                                <td><a type="button" class="layui-btn" id="addLocation"><i class="layui-icon">&#xe624;</i></a></td>
                            </tr>
                        <?php }else{ ?>
                            <tr class="box">
                                <td><input type="text" name="key[]" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入变量名" value="<?=$value ?>" ></td>
                                <td><a type="button" class="layui-btn layui-btn-danger" id="delLocation"><i class="layui-icon">&#xe67e;</i></a></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php }else{ ?>
                    <tr id="break">
                        <td><input type="text"  name="key[]" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入变量名" ></td>
                        <td><a type="button" class="layui-btn layui-btn-danger" id="delLocation"><i class="layui-icon">&#xe67e;</i></a></td>
                    </tr>
                <?php } ?>
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
            var index = layer.load('修改中',1, {shade: false, offset: '300px'});
            $.post("update",data.field,function(res){
                if(res.code==200){
                        layer.msg('修改成功', {
                                time: 2000,//3s后自动关闭
                            },function(){
                                layer.close(index);
                                window.parent.location.reload(); //刷新父页面
                            });
                }else{
                    layer.msg('修改失败'+res.message, {
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