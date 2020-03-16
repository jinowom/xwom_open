<?php

/* @var $this yii\web\View */

$this->title = '人员列表';
$pageSize = \Yii::$app->params['pageSize'];
$limitsJson = \Yii::$app->params['limitsJson'];
?>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <table class="layui-table" id="adminList" lay-filter="adminList"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/html" id="toolbar">
        <div class = "layui-btn-container" >
            <?php if(isset($t) && !empty($t)):?>
                <button class="layui-btn" lay-event="batchDel"><i class="layui-icon"></i>批量移出</button>
            <?php else:?>
                <button class="layui-btn" lay-event="batchAdd"><i class="layui-icon"></i>批量移入</button>
            <?php endif;?>
            <button class="layui-btn layui-btn-sm" lay-event="refresh">刷新</button >
        </div >
    </script>
    <script type="text/html" id="barDemo">
        <?php if(isset($t) && !empty($t)):?>
            <a class="layui-btn layui-btn-xs" lay-event="del">移出人员</a>
        <?php else:?>
            <a class="layui-btn layui-btn-xs" lay-event="add">移入人员</a>
        <?php endif;?>

    </script>
<?php
$getAdminList = \yii\helpers\Url::toRoute(['auth/get-select-admin']);
$add = \yii\helpers\Url::toRoute(['auth/select-admin']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$tableJs = <<<JS
    layui.use('table',function() {
        var table = layui.table,
            form = layui.form;
        var renderOpt = {
            id:'adminList',
            elem:'#adminList',
            toolbar:"#toolbar",
            url: '$getAdminList', //数据接口
            method:"POST", //
            where:{_csrfBackend:'$_csrfBackend',type:"$type",id:"$teamId",t:"$t"},
            page: true, //开启分页
            limit: $pageSize,
            limits: $limitsJson,
            cols: [[
                {field: 'user_id', checkbox:true,title: 'ID', width:80, sort: true, fixed: 'left'},
                {field: 'real_name', title: '真实名称', minWidth:80, sort: true,align:'center'},
                {field: 'username', title: '登录名',minWidth: 80,sort: true,align:'center'},
                {field: 'phone', title: '手机', minWidth:80,sort: true,align:'center'},
                {field: 'roleName', title: '所属角色', minWidth:80,sort: true,align:'center'},
                {field: 'created_at', title: '加入时间', minWidth:80,sort: true,align:'center'},
                {fixed: 'right', title:'操作', toolbar: '#barDemo', minWidth:150,align:'center'}
            ]]
        };
        table.render(renderOpt);
        //头工具栏事件
        table.on('toolbar(adminList)',
            function(obj) {
                var checkStatus = table.checkStatus(obj.config.id);
                switch (obj.event) {
                    case 'batchAdd':
                        var data = checkStatus.data;
                        var userIds = getColumn(data,'user_id');
                        if(userIds == null || userIds == '' || userIds == undefined){
                            layer.msg('请选择要移入的员工！',{icon:icon.ICON_WARING});
                            return false;
                        }
                        layer.confirm('确认要批量移入员工吗？',{icon: 3, title:'提示'},function(index){
                            Cajax({
                                type:"POST",
                                url:"$add",
                                data:{_csrfBackend:'$_csrfBackend',userId:userIds,id:"$teamId",type:"$type"}
                            },function () {},function(JsonData){
                                layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
                                if(JsonData.status){
                                    setTimeout("xadmin.close();", 3000 )
                                }
                            })
                        },function() {});
                        break;
                    case 'batchDel':
                        var data = checkStatus.data;
                        var userIds = getColumn(data,'user_id');
                        if(userIds == null || userIds == '' || userIds == undefined){
                            layer.msg('请选择要移出的员工！',{icon:icon.ICON_WARING});
                            return false;
                        }
                        layer.confirm('确认要批量移出员工吗？',{icon: 3, title:'提示'},function(index){
                            Cajax({
                                type:"POST",
                                url:"$add",
                                data:{_csrfBackend:'$_csrfBackend',userId:userIds,id:"$teamId",type:"$type",t:"$t"}
                            },function () {},function(JsonData){
                                layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
                                if(JsonData.status){
                                    setTimeout("xadmin.close();", 3000 )
                                }
                            })
                        },function() {});
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
            var txt = (obj.event === 'add') ? "移入" : "移出";
            layer.confirm('确认要'+txt+'该员工吗？',{icon: 3, title:'提示'},function(index){
                var data = obj.data;
                var userId = data.user_id; 
                if(obj.event === 'add'){
                    Cajax({
                        type:"POST",
                        url:"$add",
                        data:{_csrfBackend:'$_csrfBackend',userId:userId,id:"$teamId",type:"$type"}
                    },function () {},function(JsonData){
                        layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
                        if(JsonData.status){
                            setTimeout("xadmin.close();", 3000 )
                        }
                    })
                }else if(obj.event === 'del'){
                    Cajax({
                        type:"POST",
                        url:"$add",
                        data:{_csrfBackend:'$_csrfBackend',userId:userId,id:"$teamId",type:"$type",t:"$t"}
                    },function () {},function(JsonData){
                        layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
                        if(JsonData.status){
                            setTimeout("xadmin.close();", 3000 )
                        }
                    })
                }
            },function() {})
        });
     }); 
JS;
$this->registerJs($tableJs,\yii\web\View::POS_END);
?>