<?php

/* @var $this yii\web\View */

$this->title = '角色列表';
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
                    <form class="layui-form layui-col-space5">
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input" autocomplete="off" readonly placeholder="查询加入开始时间" name="start" id="start"></div>
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input" autocomplete="off" readonly placeholder="查询加入结束时间" name="end" id="end"></div>
                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" name="authName" id="authName" placeholder="请输入角色名称" autocomplete="off" class="layui-input"></div>
                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn" type="button" id="sreach" data-type="sreach" lay-filter="sreach">
                                <i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table" id="authList" lay-filter="authList"></table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/html" id="toolbar">
    <div class="layui-btn-container" >
        <button class="layui-btn" onclick="xadmin.open('添加角色','<?=\yii\helpers\Url::toRoute(['auth/add-auth'])?>',500,650)"><i class="layui-icon"></i>添加</button>
        <button class="layui-btn layui-btn-sm" lay-event="refresh">刷新</button >
    </div >
</script>
<script type="text/html" id="checkboxTpl">
    <input type="checkbox" name="lock" value="{{d.name}}" title="启用" lay-filter="lockDemo" {{ d.status == 1 ? 'checked' : '' }}>
</script>
<script type="text/html" id="barDemo">
    {{#  if(d.name == 'admin'){ }}
    {{#  } else { }}
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    {{#  } }}
    <a class="layui-btn" onclick="xadmin.open('批量移入人员','<?=\yii\helpers\Url::toRoute(['auth/select-admin'])?>&type=-1&id={{d.name}}',850,600)">批量移入</a>
    <a class="layui-btn" onclick="xadmin.open('批量移出人员','<?=\yii\helpers\Url::toRoute(['auth/select-admin'])?>&t=out&type=-1&id={{d.name}}',850,600)">批量移出</a>

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
<?php
$getAuthList = \yii\helpers\Url::toRoute(['get-auth-list']);
$update = \yii\helpers\Url::toRoute(['auth/update-auth-status']);
$delUrl = \yii\helpers\Url::toRoute(['auth/del-auth']);
$addAdmin = \yii\helpers\Url::toRoute(['auth/add-auth']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$tableJs = <<<JS
    function rowDel(obj,data,index) {
            if(data.length == 0){
                layer.msg('请选择要删除的数据', {icon: icon.ICON_WARING});
                return false;
            }
            var name = (data.length > 1) ? getColumn(data,'name') : data.name;
            layer.confirm('真的删除吗？', function(index){
                Cajax({
                    type:"POST",
                    url:"$delUrl",
                    data:{_csrfBackend:'$_csrfBackend',name:name}
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
                id:'authList',
                elem:'#authList',
                toolbar:"#toolbar",
                url: '$getAuthList', //数据接口
                method:"POST", //
                where:{_csrfBackend:'$_csrfBackend'},
                page: true, //开启分页
                limit: $pageSize,
                limits: $limitsJson,
                cols: [[
                    {field: 'id', checkbox:true,title: 'ID', width:80, sort: true, fixed: 'left'},
                    {field: 'description', title: '角色名称', minWidth:200, sort: true,align:'center'},
                    {field: 'name', title: '角色标识', minWidth:200, sort: true,align:'center'},
                    {field: 'created_at', title: '创建时间', minWidth:150, sort: true,align:'center'},
                    {field: 'updated_at', title: '更新时间', minWidth:150, sort: true,align:'center'},
                    {field: 'status', title: '状态', templet: '#checkboxTpl',minWidth:120,sort: true,align:'center'},
                    {field: 'order_sort', title: '序号', minWidth:80, sort: true,align:'center'},
                    {fixed: 'right', title:'操作', toolbar: '#barDemo', minWidth:300,align:'center'}
                ]]
            };
            table.render(renderOpt);
            //监听单元格编辑
            table.on('edit(authList)',
                function(obj) {
                    var value = obj.value //得到修改后的值
                        ,
                        data = obj.data //得到所在行所有键值
                        ,
                        field = obj.field; //得到字段
                    layer.msg('[ID: ' + data.id + '] ' + field + ' 字段更改为：' + value);
                }
            );
            //头工具栏事件
            table.on('toolbar(authList)',
                function(obj) {
                    var checkStatus = table.checkStatus(obj.config.id);
                    switch (obj.event) {
                        case 'getCheckData':
                            var data = checkStatus.data;
                            layer.alert(JSON.stringify(data));
                            break;
                        case 'getCheckLength':
                            var data = checkStatus.data;
                            layer.msg('选中了：' + data.length + ' 个');
                            break;
                        case 'isAll':
                            layer.msg(checkStatus.isAll ? '全选': '未全选');
                            break;
                        case 'delAll':
                            var data = checkStatus.data;
                            rowDel(obj,data);
                            break;
                        case 'refresh':
                            //执行重载
                            table.reload('authList', {
                                page: {
                                    curr: 1 //重新从第 1 页开始
                                }
                            }, 'data');
                            break;
                    };
                });
            //监听行工具事件
            table.on('tool(authList)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    rowDel(obj,data);
                } else if(obj.event === 'edit'){
                    xadmin.open('修改角色','$addAdmin&name='+data.name,500,550)
                } else if(obj.event === 'member_stop'){
                    member_stop(obj);
                }
            });
            form.on('checkbox(lockDemo)', function(obj){
                var _this = obj.othis;
                var checked = (obj.elem.checked == true) ? 1 : 0;
                Cajax({
                    type:"POST",
                    url:"$update",
                    data:{_csrfBackend:'$_csrfBackend',name:this.value,checked:checked}
                },function () {},function(JsonData){
                    if(JsonData.status == false){//layui-form-checked
                        (checked == 1) ? $(_this).parent('div').removeClass('layui-form-checked') : $(_this).parent('div').addClass('layui-form-checked');
                    }
                    layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
                })
            });
            
            //搜索
         var $ = layui.$, active = {
            sreach: function(){
                var start = $('#start');
                var end = $('#end');
                var authName = $('#authName');
                //执行重载
                table.reload('authList', {
                    page: { curr: 1 } ,where: {
                        start: start.val(),
                        end: end.val(),
                        authName: authName.val()
                    }
                }, 'data');
            }
          };
          $('#sreach').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
          });
        });
JS;
$this->registerJs($tableJs,\yii\web\View::POS_END);
?>