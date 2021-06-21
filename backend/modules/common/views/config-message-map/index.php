<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
                        <ul class="layui-tab-title">
                            <li class="layui-this">私信</li>
                            <li>通知</li>
                        </ul>
                        <div class="layui-tab-content">
                            <div class="layui-tab-item layui-show">
                                 <button type="button" class="layui-btn" id="reade_all">全部已读</button>
                                 <table id="demo1" lay-filter="test1"></table>
                            </div>
                            <div class="layui-tab-item">
                                 <table id="demo" lay-filter="test"></table>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
<script type="text/javascript">
layui.use(['table','form'], function(){
    var table = layui.table
       ,form = layui.form;
    //通知列表
    table.render({
    elem: '#demo'
    ,url: "pub-list" //数据接口
    ,page: true //开启分页
    ,id:'testReload'
    ,cols : [[          
        {field: 'title', title: '消息标题', align:'center'},                     
        {field: 'body', title: '消息内容', align:'center'},                           
        {field: 'created_at', title: '发送时间', align:'center'},            
    ]]
  });
  //私信列表
  table.render({
    elem: '#demo1'
    ,url: "self-list" //数据接口
    ,page: true //开启分页
    ,id:'testReload'
    ,cols : [[       
        {field: 'title', title: '消息标题', align:'center'},                    
        {field:'is_read', title:'是否已读', templet:function(d){
            if(d.is_read == 1){
                return '<span style="color:green;">已读</span>'
            }else{
                return '<span style="color:red;">未读</span>'
            }
        }},                      
        {field: 'updated_at', title: '修改时间', align:'center'},            
        {field: 'created_at', title: '添加时间', align:'center'},            
    ]]
  });
  //监听行单击事件（双击事件为：rowDouble）
    table.on('row(test1)', function(obj){
            var data = obj.data;
            var index = layer.load('修改中',1, {shade: false, offset: '300px'});
            $.get("read-one",{id:data.id},function(res){
                if(res.code==200){
                    layer.close(index);
                    layer.alert('内容：'+data.body, {
                        title: '标题：'+data.title
                        ,cancel:function(){
                          $(".layui-laypage-btn")[0].click();
                        }
                    });
                }else{
                    layer.msg(res.message, {
                                time: 2000,//3s后自动关闭
                            },function(){
                                layer.close(index);
                            });
                }
            });
        });
});

$('#reade_all').click(function(){
    var index = layer.load('修改中',1, {shade: false, offset: '300px'});
    $.get("read-all",function(res){
        if(res.code==200){
                layer.msg('成功', {
                        time: 2000,//3s后自动关闭
                    },function(){
                        layer.close(index);
                        window.location.reload(); //刷新父页面
                    });
        }else{
            layer.msg(res.message, {
                        time: 2000,//3s后自动关闭
                    },function(){
                        layer.close(index);
                    });
        }
    });
})

</script>