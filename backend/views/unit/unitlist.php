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
                    <table class="layui-table" id="uintList" lay-filter="uintList"></table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<input type="hidden" value="<?php echo $unitName;   ?>" id="unitName"/>
<script type="text/html" id="toolbar">
    <div class="layui-btn-container">
        <div class="layui-row layui-col-space10">
            <?php if($isSuper){  ?>
        <div class="layui-col-xs3 layui-col-sm2 layui-col-md1">
           <button class="layui-btn" onclick="xadmin.open('添加单位','<?=\yii\helpers\Url::toRoute(['unit/unit-edit'])?>',500,260)"><i class="layui-icon"></i>添加</button>
        </div>
            <?php  }  ?>
        <div class="layui-col-xs6 layui-col-sm6 layui-col-md6">
                <form class="layui-form layui-row layui-col-space5">
                    <div class="layui-col-xs8 layui-col-sm8 layui-col-md10">
                        <input type="text" name="unitName" id="unitName" placeholder="请输入单位名称" value="<?php echo $unitName;   ?>" autocomplete="off" class="layui-input"></div>
                    <div class="layui-col-xs4 layui-col-sm4 layui-col-md2"><button class="layui-btn" lay-submit="" lay-filter="sreach" id="searchUnit"><i class="layui-icon">&#xe615;</i></button></div>
                </form>

        </div>
          <div class="layui-col-xs3 layui-col-sm4 layui-col-md1">
           <button class="layui-btn layui-btn-sm" lay-event="refresh">刷新</button>
          </div>
            <div class="layui-col-xs3 layui-col-sm4 layui-col-md5"></div>
</div>


    </div>
</script>
<script type="text/html" id="checkboxTpl">
    <input type="checkbox" name="lock" value="{{d.unitid}}" title="启用" lay-filter="lockDemo" {{ d.u_status == 10 ? 'checked' : '' }}>
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
$getUnitList = \yii\helpers\Url::toRoute(['get-unit-list']);
$update = \yii\helpers\Url::toRoute(['unit/update-unit-status']);
$delUrl = \yii\helpers\Url::toRoute(['unit/del-unit']);
$addUnit = \yii\helpers\Url::toRoute(['unit/add-unit']);
$unitSearch = \yii\helpers\Url::toRoute(['unit/unit-list']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$tableJs = <<<JS
    function rowDel(obj,data,index) {
            if(data.length == 0){
                layer.msg('请选择要删除的数据', {icon: icon.ICON_WARING});
                return false;
            }
            var ids = (data.length > 1) ? getColumn(data,'unitid') : data.unitid;
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
                form = layui.form,
                unitName= $('#unitName').val();
            var renderOpt = {
                id:'uintList',
                elem:'#uintList',
                toolbar:"#toolbar",
                url: '$getUnitList', //数据接口
                method:"POST", //
                where:{_csrfBackend:'$_csrfBackend',unitName:unitName},
                page: true, //开启分页
                limit: $pageSize,
                limits: $limitsJson,
                cols: [[
                    {field: 'unitid', checkbox:true,title: 'ID', width:100, sort: true, fixed: 'left'},
                    {field: 'name', title: '单位名称', minWidth:100, sort: true},
                    {field: 'description', title: '单位描述', minWidth:250, sort: true},
                    {field: 'created_at', title: '创建时间', width:150, sort: true},
                    {field: 'updated_at', title: '更新时间', width:150, sort: true},
                    {field: 'u_status', title: '状态', templet: '#checkboxTpl',width:120,sort: true},
                    {fixed: 'right', title:'操作', toolbar: '#barDemo', width:130}
                ]]
            };
            table.render(renderOpt);
            //监听单元格编辑
            table.on('edit(uintList)',
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
            table.on('toolbar(uintList)',
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
                            table.reload('uintList', {
                                page: {
                                    curr: 1 //重新从第 1 页开始
                                }
                            }, 'data');
                            break;
                    };
                });
            //监听行工具事件
            table.on('tool(uintList)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    rowDel(obj,data);
                } else if(obj.event === 'edit'){
                    xadmin.open('修改单位信息','$addUnit&ids='+data.unitid,500,260);
                } else if(obj.event === 'member_stop'){
                    member_stop(obj);
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
            $(function(){
               $('body').on('click','#searchUnit',function(){
                   var unitName = $('#unitName').val();
                   /*if(unitName ==''){
                       layer.msg('请输入要查询的单位名称');return false;
                   }*/
                      table.reload('uintList', {
                                page: {
                                    curr: 1 //重新从第 1 页开始
                                },
                                where:{
                                    unitName:unitName
                                }
                            }, 'data');
                   return false;
               }); 
            });
        });
JS;
$this->registerJs($tableJs,\yii\web\View::POS_END);
?>