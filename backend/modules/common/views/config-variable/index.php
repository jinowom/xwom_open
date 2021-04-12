<?php

/* @var $this yii\web\View */

$this->title = '分页配置管理';
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
                            查询：
                        <div class="layui-inline" style="margin-right: 10px;">
                            <input class="layui-input" name="parames" id="parames" autocomplete="off" placeholder="变量名或英文名">
                        </div>
                        <button title="搜索" class="layui-btn" data-type="reload"><i class="layui-icon" style="font-size: 20px;">&#xe615;</i></button>
                    </div>
                    <script type="text/html" id="toolbarDemo">
                        <div class="layui-btn-container">
                            <button title="添加" class="layui-btn" id="create"><i class="layui-icon" style="font-size: 20px;">&#xe61f;</i>添加</button>
                            <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="getCheckData">批量删除</button>
                        </div>
                    </script>
                    <table id="demo" lay-filter="test"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<input name="_csrf" type="hidden" id="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>">
<!-- 操作列 -->
<script type="text/html" id="auth_state">
    {{#  if(d.status == 1 ){ }}
        <a class="layui-btn layui-btn-disabled layui-btn-xs"><i class="layui-icon">&#xe642;</i> </a>
        <a class="layui-btn layui-btn-disabled layui-btn-danger layui-btn-xs"><i class="layui-icon">&#xe640;</i> </a>
    {{#  }else{ }}
        <a class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon">&#xe642;</i> </a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon">&#xe640;</i> </a>
    {{#  } }}
</script>
<script type="text/html" id="switchTpl">
  <input type="checkbox" name="status" value="{{d.status}}" selfid="{{d.id}}" lay-skin="switch" lay-text="使用|不使用" lay-filter="status" {{ d.status == 1 ? 'checked' : '' }}>
</script>
<script>
layui.use(['table','form'], function(){
    var table = layui.table
       ,form = layui.form;
    table.render({
    elem: '#demo'
    ,toolbar: '#toolbarDemo'
    ,url: "<?=\yii\helpers\Url::toRoute('config-variable/index') ?>" //数据接口
    ,page: true //开启分页
    ,id:'testReload'
    ,cols: [[ //表头
       {type: 'checkbox', fixed: 'left'}
      ,{field: 'name_en', title: '变量名',sort: true, fixed: 'left',templet:function (d) {
                        return '<p style="cursor:pointer;" title="变量ID:'+d.id+'">'+d.name_en+'</p>'}}
      ,{field: 'name', title: '中文名'}
      ,{field: 'value', title: '变量值'}
      ,{field: 'description', title: '操作说明'}
      ,{field: 'type', title: '变量类型', sort: true,templet:function(d){
         if(d.type == 1){
            return '<div>前台</div>'
         }else if(d.type == 2){
            return '<div>后台</div>' 
         }else if(d.type == 3){
            return '<div>API</div>' 
         }else if(d.type ==0){
            return '<div>全局</div>' 
         }
      }} 
      ,{field:'status', title:'是否使用', templet: '#switchTpl', unresize: true}
      ,{field: 'created_at', title: '创建时间', sort: true}
      ,{field: 'updated_at', title: '修改时间', sort: true}
      ,{toolbar:"#auth_state", align: 'center',fixed:"right", title: '操作', width:210}
    ]]
  });
        //监听属性操作
  form.on('switch(status)', function(obj){
    var id = $(this).attr('selfid')
    if(this.value == 1){
        var status = 0
    }else{
        var status =1
    }
    var index = layer.load('修改中',1, {shade: false, offset: '300px'});
    $.post("<?=\yii\helpers\Url::toRoute('config-variable/update-status') ?>",{id:id,status:status},function(res){
        layer.close(index);
        if(res.code==200){
            layer.msg('修改成功', {
                time: 2000,//3s后自动关闭
            },function(){
                $(".layui-laypage-btn").click();
            });
        }else{
            layer.msg("修改失败"+res.message, {
                icon: 5 ,
                offset: 't',
                time: 3000,//3s后自动关闭
            },function () {
                $(".layui-laypage-btn").click();
            });
        }
    })
  });
  var $ = layui.$, active = {
        reload: function(){
        var parames = $('#parames');
        //执行重载
        table.reload('testReload', {
            page: {
                curr: 1 //重新从第 1 页开始
            }
            ,where: {
                parames:parames.val(),
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
            layer.confirm('确定批量删除吗？如果有配置为使用状态则会自动跳过删除。',{offset: 't'}, function(index){
                    var index = layer.load('删除中',1, {shade: false, offset: '300px'});
                    $.get("<?=\yii\helpers\Url::toRoute('config-variable/delete-all') ?>", { id : str }, function(res){
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
            layer.confirm('确定删除'+data.name+'么',{offset: 't'}, function(index){
                var index = layer.load('删除中',1, {shade: false, offset: '300px'});
                $.post("<?=\yii\helpers\Url::toRoute('config-variable/delete') ?>", send,function(res){
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
                content: "<?=\yii\helpers\Url::toRoute('config-variable/update') ?>?id="+id,
                area:['50%','50%'],
                title:'修改'
            });
        }
    })
    // 模板添加
    $(document).on('click','#create',function(){
        layer.open({
            type: 2,
            offset: 't',
            content: "<?=\yii\helpers\Url::toRoute('config-variable/create') ?>",
            area:['60%','80%'],
            title:'添加'
        });
    })
});
</script>