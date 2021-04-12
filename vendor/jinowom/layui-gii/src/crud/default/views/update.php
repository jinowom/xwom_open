<?php
/* @var $generator jinowom\Layuigii\crud\Generator */
/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}
?>
<?= "<?php ";?>/* @var $model <?= $generator->modelClass ?> */<?= " ?>";?>
<?php echo "\n" ?>

<div class="panel panel-default">
    <div class="panel-body">
    <form id="form" class="layui-form" style="width:80%;">
        <?php
        $model = new $generator->modelClass();
        foreach ($generator->getColumnNames() as $attribute) {
            if (in_array($attribute, $safeAttributes)) {
                $label = $model->getAttributeLabel($attribute);
                $item = <<<ITEM
                <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>$label</label>
                        <div class="layui-input-block">
                            <input type="text" name="$attribute" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入$label" value="<?= \$model->$attribute ?>" >
                        </div>
                    </div>
ITEM;
                $item .= "\n";
                echo $item;
            }
        } ?>
        <div class="layui-form-item">
            <button type="button" class="layui-btn" id="addbtn" style="float:right;" lay-filter="add" lay-submit="">
                确定
            </button>
        </div>
            <!-- 要将下面的value更换为 Yii::$app->request->getCsrfToken() -->
            <input name="_csrf" type="hidden" id="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
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