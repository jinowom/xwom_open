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
                <label class="layui-form-label"><span class="x-red">*</span>变量值</label>
                <div class="layui-input-block">
                     <input type="text" name="value" lay-verify="required"  autocomplete="off" placeholder="请输入变量的值" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>操作说明</label>
                <div class="layui-input-block">
                     <input type="text" name="description" lay-verify="required"  autocomplete="off" placeholder="请输入操作说明" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label"><span class="x-red"></span>变量类型</label>
                    <div class="layui-input-inline"> 
                        <select name="type" id="type" lay-search="" lay-verify="required" >
                            <option value="0">全局变量</option>
                            <option value="1">前台</option>
                            <option value="2">后台</option>
                            <option value="3">API</option>
                        </select>
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
    layui.use(['form'], function(){
        var form = layui.form
        ,layer = layui.layer
        // 提交表单
        form.on('submit(add)', function(data){
            var index = layer.load('添加中',1, {shade: false, offset: '300px'});
            $.post("<?=\yii\helpers\Url::toRoute('config-variable/create') ?>",data.field,function(res){
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
                                window.parent.location.reload(); //刷新父页面
                            });
                }
            });

            return false;
        });//监听提交
    })

</script>