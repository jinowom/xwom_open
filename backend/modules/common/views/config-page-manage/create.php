<div class="panel panel-default">
    <div class="panel-body">
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>分页变量名</label>
                <div class="layui-input-block">
                     <input type="text" name="page_name" lay-verify="required" autocomplete="off" placeholder="分页变量名" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>显示变量名</label>
                <div class="layui-input-block">
                     <input type="text" name="show_name" lay-verify="required"  autocomplete="off" placeholder="请输入显示变量名" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>控制器名</label>
                <div class="layui-input-block">
                     <input type="text" name="controller" lay-verify="required"  autocomplete="off" placeholder="如:config-page-manage" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>方法名</label>
                <div class="layui-input-block">
                     <input type="text" name="action" lay-verify="required"  autocomplete="off" placeholder="如:index" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>每页页数</label>
                <div class="layui-input-block">
                     <input type="number" name="num" lay-verify="required"  autocomplete="off" placeholder="如:index" class="layui-input" value="10">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label"><span class="x-red"></span>分页配型</label>
                    <div class="layui-input-inline"> 
                        <select name="type" id="type" lay-search="" lay-verify="required" >
                            <option value="2">后台</option>
                            <option value="1">前台</option>
                        </select>
                    </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label"><span class="x-red"></span>选择应用</label>
                    <div class="layui-input-inline"> 
                        <select name="app_id" id="app_id" lay-search="" lay-verify="required" >
                               <option value=""></option>
                            <?php foreach ($reg_soft as $key => $value) { ?>
                                <option value="<?=$value['id'] ?>"><?=$value['title'] ?></option>
                            <?php } ?>
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
            $.post("<?=\yii\helpers\Url::toRoute('config-page-manage/create') ?>",data.field,function(res){
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