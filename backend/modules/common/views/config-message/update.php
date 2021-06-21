<?php /* @var $model common\models\config\ConfigMessage */ ?>

<div class="panel panel-default">
    <div class="panel-body">
    <form id="form" class="layui-form" style="width:80%;">
    <input type="hidden" name="id" value="<?=$model->id?>">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>消息标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入消息标题" value="<?=$model->title ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>消息内容</label>
            <div class="layui-input-block">
                <input type="text" name="body" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入消息内容" value="<?=$model->body ?>" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>是否所有人</label>
            <div class="layui-input-block">
                <?php if($model->is_send_all == 1){ ?>
                    <input type="radio" name="is_send_all" value="0" title="是" lay-filter="status">
                    <input type="radio" name="is_send_all" value="1" title="否" lay-filter="status" checked>
                <?php }else{ ?>
                    <input type="radio" name="is_send_all" value="0" title="是" lay-filter="status" checked>
                    <input type="radio" name="is_send_all" value="1" title="否" lay-filter="status">
                <?php } ?>
            </div>
        </div>
        <?php if($model->is_send_all == 1){ ?>
            <div class="layui-form-item" id="box">
                <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
                    <ul class="layui-tab-title">
                        <li class="layui-this">前台用户</li>
                        <li>后台用户</li>
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <?php foreach ($member_data as $value):?>
                                <input name="member_id[]" <?=
                                    (isset($member) && in_array($value['member_id'],$member)) ? "checked" : ""
                                ?> type="checkbox" title="<?=$value['member_user']?>" value="<?=$value['member_id']?>" >
                            <?php endforeach;?>
                        </div>
                        <div class="layui-tab-item">
                          <?php foreach ($user_data as $value):?>
                                <input name="user_id[]" <?=
                                    (isset($user) && in_array($value['user_id'],$user)) ? "checked" : ""
                                ?> type="checkbox" title="<?=$value['real_name']?>" value="<?=$value['user_id']?>" >
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
            </div>
        <?php }else{ ?>
            <div class="layui-form-item" id="box" style="display:none;">
                <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
                    <ul class="layui-tab-title">
                        <li class="layui-this">前台前台用户</li>
                        <li>后台用户</li>
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                           <?php foreach ($member_data as $value):?>
                                <input name="member_id[]" type="checkbox" title="<?=$value['member_user']?>" value="<?=$value['member_id']?>">
                            <?php endforeach;?>
                        </div>
                        <div class="layui-tab-item">
                            <?php foreach ($user_data as $value):?>
                                <input name="user_id[]" type="checkbox" title="<?=$value['real_name']?>" value="<?=$value['user_id']?>" >
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
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

        //根据单选框显示不同的标签
        form.on("radio(status)", function (data) {
            var status = data.value;
            if (this.value == '0') {
                $("#box").hide();
            } else if (this.value == '1') {
                $("#box").show();
            }
        });
        $(document).ready(function(){
            $.get('get-member-info', function(e){//前台用户
                var str = ""
                $.each(e.data, function(i, v) {
                    str+='<input type="checkbox" name="member_id[]" title="'+v.member_user+'" value="'+v.member_id+'">'
                });
                $('#lanmu1').empty();
                $('#lanmu1').append(str);
                form.render();  
            })
            $.get('get-admin-info', function(e){//后台用户
                var str = ""
                $.each(e.data, function(i, v) {
                    str+='<input type="checkbox" name="user_id[]" title="'+v.real_name+'" value="'+v.user_id+'">'
                });
                $('#lanmu2').empty();
                $('#lanmu2').append(str);
                form.render();       
            })
        })

    });
</script>