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

<div class="form-content">
    <form id="form" class="layui-form" style="width:80%;">
        <?php
        foreach ($generator->getColumnNames() as $attribute) {
            if (in_array($attribute, $safeAttributes)) {
                $label = $model->getAttributeLabel($attribute);
$item = <<<ITEM
        <div class="layui-form-item layui-row layui-col-xs12">
            <label class="layui-form-label">$label</label>
            <div class="layui-input-block">
                <input name="$attribute" type="text" class="layui-input" lay-verify="" value="<?= \$model->$attribute ?>" autocomplete="off"  placeholder="请输入$label">
            </div>
        </div>
ITEM;
                $item .= "\n";
                echo $item;
            }
        } ?>
        <div class="layui-form-item layui-row layui-col-xs12">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-sm"  tid="<?= "<?= " ?>$model->id<?= " ?>" ?>" lay-filter="submit" lay-submit>提交</button>
            </div>
        </div>
    </form>
</div>

<script>
    layui.use('form', function(){
        var form = layui.form,
            $ = layui.jquery
        form.on("submit(submit)",function(data){
            var id = $(this).attr('tid');
            var url = 'edit?id=' + id;
            var data = $('#form').serialize();
            //弹出loading
            var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});
            // 实际使用时的提交信息
            $.post(url, data, function(res){
                top.layer.close(index);
                if(res.code == API_CODE.SUCCESS){
                    top.layer.msg("操作成功！");
                    //刷新父级页面
                    var pIndex = parent.layer.getFrameIndex(window.name)
                    if(pIndex){
                        parent.layer.close(pIndex)
                    }
                }else{
                    top.layer.msg(res.msg);
                }
            })
            return false;
        })
    });
</script>