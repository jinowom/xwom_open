<?php /* @var $model common\models\config\ConfigIpmanage */ ?>

<div class="panel panel-default">
    <div class="panel-body">
    <form id="form" class="layui-form" style="width:80%;">
            <input type = "hidden" name='id' value="<?= $model->id ?>">
                        <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>IP地址</label>
                        <div class="layui-input-block">
                            <input type="text" name="ip" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Ip" value="<?= $model->ip ?>" >
                        </div>
                    </div>
                <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>开始时间</label>
                        <div class="layui-input-block">
                            <input type="text" name="start_time" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入Start Time" id="start" value="<?= $model->start_time ?>" >
                        </div>
                    </div>
                <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>结束时间</label>
                        <div class="layui-input-block">
                            <input type="text" name="end_time" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入End Time" id="end" value="<?= $model->end_time ?>" >
                        </div>
                    </div>
            
        <div class="layui-form-item">
            <button type="button" class="layui-btn" id="addbtn" style="float:right;" lay-filter="add" lay-submit="">
                确定
            </button>
        </div>
            <!-- 要将下面的value更换为 Yii::$app->request->getCsrfToken() -->
            <input name="_csrf" type="hidden" id="_csrf" value="KAvUuUZvcYBD7dVt4Po-JW4Uq_XcUfG6r_OC8HmaDggYeJfDNB4l8Ryqtz-4nGgSBFnlmI0Lpdb4l-yzNq9Yeg==">
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
<script>
    $(function () {
        layui.use('laydate',
            function() {
                var laydate = layui.laydate;
                //执行一个laydate实例
                laydate.render({
                    elem: '#start' //指定元素
                });
                //执行一个laydate实例
                laydate.render({
                    elem: '#end' //指定元素
                });
            });
    })
</script>