<?php

/* @var $this yii\web\View */

$this->title = $title;
$pageSize = \Yii::$app->params['pageSize'];
$limitsJson = \Yii::$app->params['limitsJson'];
?>
<!-- 面包屑 -->
<?= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')?>
<!-- 面包屑 -->
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <table class="layui-table" id="table" lay-filter="table"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="toolbar">
    <div class = "layui-btn-container" >
        <button class="layui-btn" onclick="xadmin.open('添加类型','<?=\yii\helpers\Url::toRoute(['add-type'])?>',500,550)"><i class="layui-icon"></i>添加</button>
        <button class="layui-btn layui-btn-sm" lay-event="refresh">刷新</button >
    </div >
</script>
<script type="text/html" id="checkboxTpl">
    <input type="checkbox" name="lock" value="{{d.id}}" title="启用" lay-filter="lockDemo" {{ d.status == 1 ? 'checked' : '' }}>
</script>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<?php
$getList = \yii\helpers\Url::toRoute(['get-type']);
$update = \yii\helpers\Url::toRoute(['add-type']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$tableJs = <<<JS
    function rowDel(obj,data,index) {
        if(data.length == 0){
            layer.msg('请选择要删除的数据', {icon: icon.ICON_WARING});
            return false;
        }
        var ids = (data.length > 1) ? getColumn(data,'id') : data.id;
        layer.confirm('真的删除吗？', function(index){
            Cajax({
                type:"POST",
                url:"$update",
                data:{_csrfBackend:'$_csrfBackend',id:ids,checked:-1}
            },function(){},function(JsonData){
                layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
                if(JsonData.status){
                    obj.del();
                    layer.close(index);
                }
            })
        });
    }
    layui.use('table',function() {
        var table = layui.table,
            form = layui.form;
        var renderOpt = {
            elem:'#table',
            toolbar:"#toolbar",
            url: '$getList', //数据接口
            method:"POST", //
            where:{_csrfBackend:'$_csrfBackend',type:'$type'},
            page: true, //开启分页
            limit: $pageSize,
            limits: $limitsJson,
            cols: [[
                {field: 'otype', width:80, fixed: 'left',hide:true},
                {field: 'id', checkbox:false, title: '编号', width:80, fixed: 'left'},
                {field: 'type', title: '类型名称', minWidth:100, width:100,edit: 'text'},
                {field: 'status', title: '状态', templet: '#checkboxTpl',minWidth:150,width:130},
                {fixed: 'right', title:'操作', toolbar: '#barDemo', width:80}
            ]]
        };
        table.render(renderOpt);
        //头工具栏事件
        table.on('tool(table)', function(obj){
            var data = obj.data;
            if(obj.event === 'del'){
                rowDel(obj,data);
            }
        });
        table.on('toolbar(table)',
            function(obj) {
                var checkStatus = table.checkStatus(obj.config.id);
                switch (obj.event) {
                    case 'refresh':
                        //执行重载
                        table.reload('table', {
                            page: {
                                curr: 1 //重新从第 1 页开始
                            }
                        }, 'data');
                        break;
                };
            });
        //监听行工具事件        
        form.on('checkbox(lockDemo)', function(obj){
            var _this = obj.othis;
            var checked = (obj.elem.checked == true) ? 1 : 0;
            Cajax({
                type:"POST",
                url:"$update",
                data:{_csrfBackend:'$_csrfBackend',id:this.value,checked:checked}
            },function () {},function(JsonData){
                if(JsonData.status == false){//layui-form-checked
                    (checked == 1) ? $(_this).parent('div').removeClass('layui-form-checked') : $(_this).parent('div').addClass('layui-form-checked');
                }
                layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
            })
        });
        table.on('edit(table)', function(obj){
            var val = obj.value,ids = obj.data.id,type=obj.data.otype;
            layer.confirm('确定修改吗？', function(index){
                Cajax({
                    type:"POST",
                    url:"$update",
                    data:{_csrfBackend:'$_csrfBackend',id:ids,name:val}
                },function(){
                    layer.closeAll();
                    load = layer.load();
                },function(JsonData){
                    layer.closeAll();
                    layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
                    if(JsonData.status){
                        obj.update({'type':val});
                        layer.close(index);
                    }
                })
            },function(index){
                obj.update({'type':type});
            });
        });
     }); 
JS;
$this->registerJs($tableJs,\yii\web\View::POS_END);
?>