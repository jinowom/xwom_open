<?php /* @var $model common\models\config\ConfigOss */ ?>

<div class="panel panel-default">
    <div class="panel-body">
    <form id="form" class="layui-form" style="width:80%;">
    <input type = "hidden" name = 'id' value="<?= $model->id ?>">
                        <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>阿里ID</label>
                        <div class="layui-input-block">
                            <input type="text" name="access_key_id" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Access Key ID" value="<?= $model->access_key_id ?>" >
                        </div>
                    </div>
                <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>阿里API秘钥</label>
                        <div class="layui-input-block">
                            <input type="text" name="access_key_secret" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Access Key Secret" value="<?= $model->access_key_secret ?>" >
                        </div>
                    </div>
                <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>阿里bucket域名</label>
                        <div class="layui-input-block">
                            <input type="text" name="bucket" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Bucket" value="<?= $model->bucket ?>" >
                        </div>
                    </div>
                <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>sdk配置项地域节点</label>
                        <div class="layui-input-block">
                            <input type="text" name="endpoint" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Endpoint" value="<?= $model->endpoint ?>" >
                        </div>
                    </div>
                <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>oss地址</label>
                        <div class="layui-input-block">
                            <input type="text" name="url" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Url" value="<?= $model->url ?>" >
                        </div>
                    </div>
                <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>本地地址</label>
                        <div class="layui-input-block">
                            <input type="text" name="local_url" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Local Url" value="<?= $model->local_url ?>" >
                        </div>
                    </div>
                <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>描述</label>
                        <div class="layui-input-block">
                            <input type="text" name="description" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Description" value="<?= $model->description ?>" >
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