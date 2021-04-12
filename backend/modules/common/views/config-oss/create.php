<?php /* @var $model common\models\config\ConfigOss */ ?>

<div class="panel panel-default">
    <div class="panel-body">
    <form id="form" class="layui-form" style="width:80%;">
                        <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>阿里ID</label>
                    <div class="layui-input-block">
                        <input type="text" name="access_key_id" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Access Key ID" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>阿里API秘钥</label>
                    <div class="layui-input-block">
                        <input type="text" name="access_key_secret" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Access Key Secret" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>阿里bucket域名</label>
                    <div class="layui-input-block">
                        <input type="text" name="bucket" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Bucket" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>sdk配置项地域节点</label>
                    <div class="layui-input-block">
                        <input type="text" name="endpoint" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Endpoint" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>oss地址</label>
                    <div class="layui-input-block">
                        <input type="text" name="url" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Url" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>本地地址</label>
                    <div class="layui-input-block">
                        <input type="text" name="local_url" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Local Url" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>描述</label>
                    <div class="layui-input-block">
                        <input type="text" name="description" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Description" >
                    </div>
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