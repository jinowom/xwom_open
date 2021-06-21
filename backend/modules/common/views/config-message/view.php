<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <table id="demo" lay-filter="test"></table>
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
    table.render({
    elem: '#demo'
    ,toolbar: '#toolbarDemo'
    ,url: "view?id="+<?=$id?> //数据接口
    ,page: true //开启分页
    ,id:'testReload'
    ,cols : [[
        {type: 'checkbox', fixed: 'left'},           
        {field: 'title', title: '消息标题', width: '', align:'center'},            
        {field: 'username', title: '接收人', width: '', align:'center'},            
        {field:'is_read', title:'是否已读', templet:function(d){
            if(d.is_read == 1){
                return '已读'
            }else{
                return '未读'
            }
        }},    
        {field:'read_time', title:'阅读时间', unresize: true},                        
        {field: 'updated_at', title: '修改时间', width: '', align:'center'},            
        {field: 'created_at', title: '添加时间', width: '', align:'center'},            
    ]]
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
});

</script>