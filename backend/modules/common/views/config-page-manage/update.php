<div class="panel panel-default">
    <div class="panel-body">
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>分页变量名</label>
                <div class="layui-input-block">
                     <input type="text" name="page_name" lay-verify="required" autocomplete="off" placeholder="分页变量名" class="layui-input" value="<?=$model->page_name ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>显示变量名</label>
                <div class="layui-input-block">
                     <input type="text" name="show_name" lay-verify="required"  autocomplete="off" placeholder="请输入显示变量名" class="layui-input" value="<?=$model->show_name ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>控制器名</label>
                <div class="layui-input-block">
                     <input type="text" name="controller" lay-verify="required"  autocomplete="off" placeholder="如:config-page-manage" class="layui-input" value="<?= $model->controller ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>方法名</label>
                <div class="layui-input-block">
                     <input type="text" name="action" lay-verify="required"  autocomplete="off" placeholder="如:index" class="layui-input" value="<?= $model->action ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>每页页数</label>
                <div class="layui-input-block">
                     <input type="number" name="num" lay-verify="required"  autocomplete="off" placeholder="如:index" class="layui-input" value="<?= $model->num ?>">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label"><span class="x-red"></span>分页配型</label>
                    <div class="layui-input-inline"> 
                        <select name="type" id="type" lay-search="">
                            <?php if($model->type == 2){ ?>
                                <option value="2">后台</option>
                                <option value="1">前台</option>
                            <?php } else { ?>
                                <option value="1">前台</option>
                                <option value="2">后台</option>
                            <?php } ?>
                        </select>
                    </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label"><span class="x-red"></span>选择应用</label>
                    <div class="layui-input-inline"> 
                        <select name="app_id" id="app_id" lay-search="" lay-verify="required" >
                            <?php foreach ($reg_soft as $key => $value) { ?>
                                <?php if($value['id'] == $model->app_id){ ?>
                                    <option value="<?=$value['id'] ?>"><?=$value['title'] ?></option>
                                <?php } ?>
                            <?php } ?>
                            <?php foreach ($reg_soft as $key => $value) { ?>
                                <?php if($value['id'] != $model->app_id){ ?>
                                    <option value="<?=$value['id'] ?>"><?=$value['title'] ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
            </div>
            <div class="layui-form-item">
                <button type="button" class="layui-btn" id="addbtn" style="float:right;" lay-filter="add" lay-submit="">
                    确定
                </button>
            </div>
            <input type="hidden" name = 'id' value="<?=$model->id ?>" >
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
            var index = layer.load('修改中',1, {shade: false, offset: '300px'});
            $.post("<?=\yii\helpers\Url::toRoute('config-page-manage/update') ?>",data.field,function(res){
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
                            window.parent.location.reload(); //刷新父页面
                        });
                }
            });

            return false;
        });//监听提交
    })

</script>