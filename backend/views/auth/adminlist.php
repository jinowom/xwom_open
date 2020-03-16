<?php

/* @var $this yii\web\View */

$this->title = '管理员列表';
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
                <div class="layui-card-body">
                    <form class="layui-form layui-col-space5">
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input" autocomplete="off" readonly placeholder="查询加入开始时间" name="start" id="start"></div>
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input" autocomplete="off" readonly placeholder="查询加入结束时间" name="end" id="end"></div>
                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" name="bind_phone" id="bind_phone" placeholder="请输入手机号码" autocomplete="off" class="layui-input"></div>
                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn" type="button" id="sreach" data-type="sreach" lay-filter="sreach">
                                <i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table" id="adminList" lay-filter="adminList"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="toolbar">
    <div class = "layui-btn-container" >
        <button class="layui-btn" onclick="xadmin.open('添加用户','<?=\yii\helpers\Url::toRoute(['auth/add-admin'])?>',500,550)"><i class="layui-icon"></i>添加</button>
        <button class="layui-btn layui-btn-sm" lay-event="refresh">刷新</button >
    </div >
</script>
<script type="text/html" id="checkboxTpl">
    <input type="checkbox" name="lock" value="{{d.user_id}}" title="启用" lay-filter="lockDemo" {{ d.status == 10 ? 'checked' : '' }}>
</script>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
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
$getAdminList = \yii\helpers\Url::toRoute(['get-admin-list']);
$update = \yii\helpers\Url::toRoute(['auth/update-admin-status']);
$delUrl = \yii\helpers\Url::toRoute(['auth/del-admin']);
$addAdmin = \yii\helpers\Url::toRoute(['auth/add-admin']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$tableJs = <<<JS
     function rowDel(obj,data,index) {
            if(data.length == 0){
                layer.msg('请选择要删除的数据', {icon: icon.ICON_WARING});
                return false;
            }
            var ids = (data.length > 1) ? getColumn(data,'user_id') : data.user_id;
            layer.confirm('真的删除吗？', function(index){
                Cajax({
                    type:"POST",
                    url:"$delUrl",
                    data:{_csrfBackend:'$_csrfBackend',ids:ids}
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
            id:'adminList',
            elem:'#adminList',
            toolbar:"#toolbar",
            url: '$getAdminList', //数据接口
            method:"POST", //
            where:{_csrfBackend:'$_csrfBackend'},
            page: true, //开启分页
            limit: $pageSize,
            limits: $limitsJson,
            cols: [[
                {field: 'user_id', checkbox:true,title: 'ID', width:80, sort: true, fixed: 'left'},
                {field: 'real_name', title: '真实名称', minWidth:150, sort: true},
                {field: 'username', title: '登录名',minWidth: 150,sort: true},
                {field: 'phone', title: '手机', minWidth:150,sort: true},
                {field: 'email', title: '邮箱', minWidth:150,sort: true},
                {field: 'roleName', title: '角色', minWidth:100,sort: true},
                {field: 'unit', title: '单位', minWidth:100,sort: true},
                {field: 'status', title: '状态', templet: '#checkboxTpl',minWidth:120,sort: true,},
                {field: 'created_at', title: '加入时间', minWidth:150,sort: true, },
                {fixed: 'right', title:'操作', toolbar: '#barDemo', width:150}
            ]]
        };
        table.render(renderOpt);
        //头工具栏事件
        table.on('toolbar(adminList)',
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
                        table.reload('adminList', {
                            page: {
                                curr: 1 //重新从第 1 页开始
                            }
                        }, 'data');
                        break;
                };
            });
        //监听行工具事件
        table.on('tool(adminList)', function(obj){
            var data = obj.data;
            if(obj.event === 'del'){
                rowDel(obj,data);
            } else if(obj.event === 'edit'){
                xadmin.open('修改用户','$addAdmin&userId='+data.user_id,500,550)
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
                data:{_csrfBackend:'$_csrfBackend',ids:this.value,checked:checked}
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
                var bindPhone = $('#bind_phone');
                //执行重载
                table.reload('adminList', {
                    page: { curr: 1 } ,where: {
                        start: start.val(),
                        end: end.val(),
                        bindPhone: bindPhone.val()
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