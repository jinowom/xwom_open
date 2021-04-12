<div class="panel panel-default">
    <div class="panel-body">
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>变量名</label>
                <div class="layui-input-block">
                     <input type="text" name="name_en" lay-verify="required" autocomplete="off" class="layui-input" placeholder="变量名如：BIANLIANG" value="<?=$data->name_en ?>" >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>显示变量名</label>
                <div class="layui-input-block">
                     <input type="text" name="name" lay-verify="required"  autocomplete="off" placeholder="请输入显示变量名" class="layui-input" value="<?=$data->name ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>变量值</label>
                <div class="layui-input-block">
                     <input type="text" name="value" lay-verify="required"  autocomplete="off" placeholder="请输入变量的值" class="layui-input" value="<?=$data->value ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>操作说明</label>
                <div class="layui-input-block">
                     <input type="text" name="description" lay-verify="required"  autocomplete="off" placeholder="请输入操作说明" class="layui-input" value="<?=$data->description ?>">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label"><span class="x-red"></span>变量类型</label>
                    <div class="layui-input-inline"> 
                        <select name="type" id="type" lay-search="" lay-verify="required" >
                            <?php if($data->type == 2){ ?>
                                <option value="2">后台</option>
                                <option value="0">全局</option>
                                <option value="1">前台</option>
                                <option value="3">API</option>
                            <?php } else if($data->type == 1) { ?>
                                <option value="1">前台</option>
                                <option value="0">全局</option>
                                <option value="2">后台</option>
                                <option value="3">API</option>
                            <?php } else if($data->type == 0){ ?>
                                <option value="0">全局变量</option>
                                <option value="1">前台</option>
                                <option value="2">后台</option>
                                <option value="3">API</option>
                            <?php } else if($data->type == 3){ ?>
                                <option value="3">API</option>
                                <option value="0">全局变量</option>
                                <option value="1">前台</option>
                                <option value="2">后台</option>
                            <?php } ?>
                        </select>
                    </div>
            </div>
            <div class="layui-form-item">
                <button type="button" class="layui-btn" id="addbtn" style="float:right;" lay-filter="add" lay-submit="">
                    确定
                </button>
            </div>
               <input type="hidden" name = 'id' value="<?=$data->id ?>" >
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
            $.post("<?=\yii\helpers\Url::toRoute('config-variable/update') ?>", data.field,function(res){
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