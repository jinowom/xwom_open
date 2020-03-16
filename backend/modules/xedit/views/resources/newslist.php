<?php

/* @var $this yii\web\View */

$this->title = '资源稿库管理';
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
                            <input class="layui-input" autocomplete="off" readonly placeholder="请选择投稿的开始时间" name="start" id="start"></div>
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input" autocomplete="off" readonly placeholder="请选择投稿的结束时间" name="end" id="end"></div>
                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" name="wd" id="wd" placeholder="请输入稿件标题" autocomplete="off" class="layui-input"></div>
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
        <button class="layui-btn" onclick="xadmin.open('添加资源稿件','<?=\yii\helpers\Url::toRoute(['resources/add-new'])?>','','',true)"><i class="layui-icon"></i>添加资源稿件</button>
        <button class="layui-btn layui-btn-sm" lay-event="getNews" >取稿</button >
        <button class="layui-btn layui-btn-sm" lay-event="refresh">刷新</button >
    </div >
</script>
<script type="text/html" id="checkboxTpl">
    <input type="checkbox" name="lock" value="{{d.user_id}}" title="启用" lay-filter="lockDemo" {{ d.status == 10 ? 'checked' : '' }}>
</script>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="send">分发</a>
</script>
<script type="text/html" id="selectS">
    <a class="layui-btn layui-btn-disabled" lay-event="disabled">禁用按钮</a>
</script>
<script type="text/html" id="selectRec">
    <a class="layui-btn" lay-event="selectRec">查看记录</a>
</script>
<div id="getRec" style="display: none; padding: 0px 10px;">
    <table class="layui-table" width="90%" style="text-align: center">
        <colgroup>
            <col width="30%">
            <col width="30%">
            <col width="30%">
            <col>
        </colgroup>
        <thead>
        <tr>
            <th style="text-align: center">取搞类型</th>
            <th style="text-align: center">取搞媒体</th>
            <th style="text-align: center">取搞时间</th>
            <th style="text-align: center">取搞状态</th>
        </tr>
        </thead>
        <tbody id="showRec"></tbody>
    </table>
</div>
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
$getAdminList = \yii\helpers\Url::toRoute(['get-news-list']);
$update = \yii\helpers\Url::toRoute(['auth/update-admin-status']);
$delUrl = \yii\helpers\Url::toRoute(['resources/del-new']);
$addAdmin = \yii\helpers\Url::toRoute(['resources/add-new']);
$getNews = \yii\helpers\Url::toRoute(['resources/get-news']);
$getRecords = \yii\helpers\Url::toRoute(['resources/get-news-records']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$tableJs = <<<JS
    function getNews(obj,data,index){
        if(data.length == 0){
            layer.msg('请选择要取稿的稿件', {icon: icon.ICON_WARING});
            return false;
        }
        var ids = (data.length > 0) ? getColumn(data,'id') : '';
        ids = ids.join(',');
        xadmin.open('取稿','{$getNews}&ids='+ids,'500','500',false);
    }
    function rowDel(obj,data,index) {
        if(data.length == 0){
            layer.msg('请选择要删除的数据', {icon: icon.ICON_WARING});
            return false;
        }
        var ids = (data.length > 1) ? getColumn(data,'id') : data.id;
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
                {field: 'id', checkbox:true, title: 'ID', width:80, fixed: 'left'},
                {field: 'title', title: '稿件标题', minWidth:100, width:400},
                {field: 'author', title: '稿件作者',minWidth: 20, width:100},
                {field: 'unit_name', title: '所属单位', minWidth:50, width:200},
                {field: 'content_length', title: '字数', minWidth:50, width:80},
                {field: 't_date', title: '投稿时间', minWidth:50, width:150},
                {field: 'created', title: '审核轨迹', minWidth:100, width:150, toolbar: '#selectS'},
                {field: 'status', title: '取搞记录',minWidth:150, width:120, toolbar:'#selectRec'},
                {fixed: 'right', title:'操作', toolbar: '#barDemo', width:200}
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
                    case 'getNews':
                        var data = checkStatus.data;
                        getNews(obj,data);
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
                xadmin.open('添加资源稿件','$addAdmin&newId='+data.id,'','',true)
            } else if(obj.event === 'member_stop'){
                member_stop(obj);
            } else if(obj.event === 'selectRec'){
                Cajax({
                    type:"POST",
                    url:"$getRecords",
                    data:{_csrfBackend:'$_csrfBackend',id:data.id}
                },function(){},function(JsonData){
                   if(JsonData != ''){
                       layer.open({
                          area: ['550px', '400px'],
                          type: 1,
                          shade: false,
                          title: '取搞记录',
                          content: $('#getRec'), //捕获的元素
                          cancel: function(){}
                       });
                       var s = '';
                       for (i = 0; i < JsonData.length; i++){
                           s += '<tr><td>'+JsonData[i].type+'</td>';
                           s += '<td>'+JsonData[i].name+'</td>';
                           s += '<td>'+JsonData[i].collecttime+'</td>';
                           s += '<td>'+JsonData[i].statusName+'</td></tr>';
                       }
                       $("#showRec").html(s);
                       return true;
                   }
                   layer.msg('该暂无取搞记录',)
                });
               
            }
        });
        form.on('checkbox(lockDemo)', function(obj){
            var checked = (obj.elem.checked == true) ? 1 : 0;
            Cajax({
                type:"POST",
                url:"$update",
                data:{_csrfBackend:'$_csrfBackend',ids:this.value,checked:checked}
            },function () {},function(JsonData){
                layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
            })
        });
        //搜索
         var $ = layui.$, active = {
            sreach: function(){
                var start = $('#start');
                var end = $('#end');
                var wd = $('#wd');
                //执行重载
                table.reload('adminList', {
                    page: { curr: 1 } ,where: {
                        start: start.val(),
                        end: end.val(),
                        wd: wd.val()
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