<?php

/* @var $this yii\web\View */

$this->title = '栏目管理';
?>
 <!-- 面包屑 -->
<?= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')?>
<!-- 面包屑 -->
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <div class="demoTable" style="margin: 1px 0;margin-left: 0.5%;">
                        <form class="layui-form" action="">
                            查询：
                        <div class="layui-inline" style="margin-right: 10px;">
                            <input class="layui-input" name="parames" id="parames" autocomplete="off" placeholder="请输入搜索条件">
                        </div>
                            行为类型:  
                            <div class="layui-input-inline"> 
                                <select name="behavior" id="behavior"  lay-search="" >
                                    <option value=""></option>
                                    <option value="1">页面浏览</option>
                                    <option value="2">查询数据</option>
                                    <option value="3">增加数据</option>
                                    <option value="4">修改数据</option>
                                    <option value="5">删除数据</option>
                                </select>
                            </div>
                            类型:  
                            <div class="layui-input-inline"> 
                                <select name="status" id="status"  lay-search="" >
                                    <option value=""></option>
                                    <option value="0">全局</option>
                                    <option value="1">前台</option>
                                    <option value="2">后台</option>
                                    <option value="3">API</option>
                                </select>
                            </div>
                            <a title="搜索" class="layui-btn" data-type="reload"><i class="layui-icon" style="font-size: 20px;">&#xe615;</i></a>
                            </form>
                    </div>
                    <script type="text/html" id="toolbarDemo">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="getCheckData">批量删除</button>
                        </div>
                    </script>
                    <table id="demo" lay-filter="test"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
<!-- 操作列 -->
<script type="text/html" id="auth_state">
    <a class="layui-btn layui-btn-xs" lay-event="view"><i class="layui-icon">&#xe63c;</i> </a>
</script>
<script type="text/javascript">
layui.use(['table','form'], function(){
    var table = layui.table
       ,form = layui.form;
    table.render({
    elem: '#demo'
    ,toolbar: '#toolbarDemo'
    ,url: "index" //数据接口
    ,page: true //开启分页
    ,id:'testReload'
    ,cols : [[
        {type: 'checkbox', fixed: 'left'},                      
        {field: 'user_id', title: '用户ID', width: '120', align:'center'},            
        {field: 'behavior', title: '行为类别', width: '120', align:'center',templet:function(d){
            if(d.behavior == 1){
                return "页面浏览"
            }else if(d.behavior == 2){
                return "查询数据"
            }else if(d.behavior == 3){
                return "添加数据"
            }else if(d.behavior == 4){
                return "修改数据"
            }else if(d.behavior == 5){
                return "删除数据"
            }
        }},            
        {field: 'method', title: '提交方式', width: '120', align:'center'},            
        {field: 'module', title: '模块', width: '120', align:'center'},            
        {field: 'controller', title: '控制器', width: '120', align:'center'},            
        {field: 'action', title: '方法', width: '120', align:'center'},            
        {field: 'url', title: '地址', width: '120', align:'center'},                       
        {field: 'ip', title: 'Ip', width: '120', align:'center'},                                
        {field: 'status', title: '类型', width: '120', align:'center',templet:function(d){
            if(d.status == 0){
                return "全局"
            }else if(d.status == 1){
                return "前台"
            }else if(d.status == 2){
                return "后台"
            }else if(d.status == 3){
                return "API"
            }
        }},                             
        {field: 'remark', title: '日志备注', width: '200', align:'center'},
        {field: 'created_at', title: '添加时间', width: '200', align:'center'},                      
        {title: '操作', width:80, templet:'#auth_state',fixed:"right", align:"center"}
    ]]
  });
  var $ = layui.$, active = {
        reload: function(){
        var parames = $('#parames');
        var behavior = $('#behavior')
        var status = $('#status')
        //执行重载
        table.reload('testReload', {
            page: {
                curr: 1 //重新从第 1 页开始
            }
            ,where: {
                parames:parames.val(),
                behavior:behavior.val(),
                status:status.val(),
            }
            });
        }
    };
    //点击搜索按钮
    $('.demoTable .layui-btn').on('click', function(){
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
    });
         //头工具栏事件
    table.on('toolbar(test)', function(obj){
        var checkStatus = table.checkStatus(obj.config.id);
        switch(obj.event){
        case 'getCheckData':
            var data = checkStatus.data;
            var str = ""
            $.each(data,function(i,val){
                str+=','+val.id
            })
            layer.confirm('确定批量删除吗？',{offset: 't'}, function(index){
                    var index = layer.load('删除中',1, {shade: false, offset: '300px'});
                    $.get("delete-all", { id : str }, function(res){
                    layer.close(index);
                    if(res.code==200){
                        layer.msg('删除成功', {
                            time: 2000,//3s后自动关闭
                        },function(){
                            $(".layui-laypage-btn")[0].click();
                        });
                        }else{
                            layer.alert("删除失败"+res.message, {icon: 5 ,offset: 't'},function () {
                                $(".layui-laypage-btn")[0].click();
                            });
                        }
                    })
                });
        break;
        }
    });

    //监听工具条
    table.on('tool(test)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        var data = obj.data; //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr; //获得当前行 tr 的DOM对象
        var id = data.id;
        if(layEvent === 'del'){ //删除
            var csrfToken = $('#_csrf').val();
            var csrd={_csrf:csrfToken};
            var send={id:id};
            Object.assign(send,csrd);
            layer.confirm('确定删除么',{offset: 't'}, function(index){
                var index = layer.load('删除中',1, {shade: false, offset: '300px'});
                $.post("delete", send,function(res){
                 layer.close(index);
                if(res.code==200){
                    layer.msg('删除成功', {
                         time: 2000,//3s后自动关闭
                     },function(){
                         $(".layui-laypage-btn")[0].click();
                     });
                    }else{
                        layer.msg("删除失败"+res.message, {
                            icon: 5 ,
                            offset: 't',
                            time: 3000,//3s后自动关闭
                        },function () {
                            $(".layui-laypage-btn")[0].click();
                        });
                    }
                })
            //向服务端发送删除指令
            });
        }else if(layEvent ==='edit'){
            layer.open({
                type: 2,
                offset: 't',
                content: "update?id="+id,
                area:['60%','80%'],
                title:'修改'
            });
        }else if(layEvent === 'view'){
            layer.open({
                type: 2,
                offset: 't',
                content: "view?id="+id,
                area:['60%','80%'],
                title:'查看'
            });
        }
    })
    // 模板添加
    $(document).on('click','#create',function(){
        layer.open({
            type: 2,
            offset: 't',
            content: "create",
            area:['60%','80%'],
            title:'添加'
        });
    })
});

</script>