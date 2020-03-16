<?php

/* @var $this yii\web\View */

$this->title = '角色列表';
$pageSize = \Yii::$app->params['pageSize'];
$limitsJson = \Yii::$app->params['limitsJson'];
use common\utils\ToolUtil;
$getDepAsync = \yii\helpers\Url::toRoute(['dep/get-dep-list-by-unit-id']);
$_csrfBackendAsync = \Yii::$app->request->csrfToken;
$getMemberList = \yii\helpers\Url::toRoute(['dep/get-member-list']);
$getDepList = \yii\helpers\Url::toRoute(['get-dep-list']);
?>
<!-- 面包屑 -->
<?= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')?>
<!-- 面包屑 -->
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md3">
                <div class="layui-card">
                    <input type="hidden" value="" id="hasUnitId">
                    <input type="hidden" value="" id="currentDepid">
                    <input type="hidden" value="" id="currentUnitid">
                    <div class="layui-card-body ztree"  id="depController">
                        <?php
                        $this->registerJsFile('@web/lib/ztree/js/jquery.ztree.core.js');
                        $this->registerCssFile('@web/lib/ztree/css/zTreeStyle/zTreeStyle.css');
                        ?>
                        <script>
                            let setting = {
                                view: {
                                    selectedMulti: false
                                },
                                async: {
                                    enable: true,
                                    url: '<?php echo $getDepAsync;?>',
                                    autoParam:["unitid=unitid", "name=name","depid=depid"],
                                    otherParam:{"otherParam":"depAsync","_csrfBackend":'<?php  echo $_csrfBackendAsync;  ?>'},
                                    dataFilter: filter
                                },
                                callback: {
                                    beforeClick: beforeClick,
                                    beforeAsync: beforeAsync,
                                    onAsyncError: onAsyncError,
                                    onAsyncSuccess: onAsyncSuccess
                                }
                            };
                            function filter(treeId, parentNode, childNodes) {
                                console.log(treeId, parentNode, childNodes);
                                if (!childNodes) return null;
                                for (var i=0, l=childNodes.length; i<l; i++) {
                                    childNodes[i].name = childNodes[i].name;
                                }
                                return childNodes;
                            }

                            function beforeClick(treeId, treeNode) {
                                console.log(treeId, treeNode);
                                if(treeNode.unitid){
                                    $('#hasUnitId').val(treeNode.unitid);
                                }
                                $('#currentDepid').val(treeNode.depid);
                                $('#currentUnitid').val(treeNode.unitid);
                                var unitId = $('#currentUnitid').val();
                                var depId = $('#currentDepid').val();
                                layui.use('table',function() {
                                    var table = layui.table;
                                    table.reload('memberList', {
                                        url: '<?php  echo $getMemberList; ?>'
                                        , where: {_csrfBackend: '<?php  echo $_csrfBackendAsync; ?>', depId: depId}
                                    });

                                    table.reload('depList', {
                                        url: '<?php  echo $getDepList; ?>'
                                        , where: {_csrfBackend: '<?php  echo $_csrfBackendAsync; ?>', depId: depId,unitId:unitId}
                                    });

                                });
                                 return;
                            }
                            let log, className = "dark";
                            function beforeAsync(treeId, treeNode) {
                                className = (className === "dark" ? "":"dark");
                                return true;
                            }
                            function onAsyncError(event, treeId, treeNode, XMLHttpRequest, textStatus, errorThrown) {

                            }
                            function onAsyncSuccess(event, treeId, treeNode, msg) {
                            }
                            $(document).ready(function(){
                                $.fn.zTree.init($("#depController"), setting);
                            });
                            </script>

                    </div>
                </div>
        </div>
        <div class="layui-col-md9">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <table class="layui-table" id="depList" lay-filter="depList"></table>
                </div>
                <div class="layui-card-body">
                    <a class="layui-btn layui-btn-xs" id="batchImport">批量移入人员</a>
                    <a class="layui-btn layui-btn-xs" id="batchExport">批量移出人员</a>
                    <table class="layui-table" id="memberList" lay-filter="memberList"></table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="<?php echo $isSuper; ?>" id="isSuper"/>
</div>
    <script type="text/html" id="usernameTpl">
        {{# if(d.dep_isleader == 1){ }}
        {{ d.username }} <i style="color:#f23030;" class="icon iconfont">&#xe726;</i>
        {{#  } else { }}
        {{ d.username }}
        {{#  } }}
</script>
    <input type="hidden" value="<?php echo $depName;   ?>" id="depName"/>
<script type="text/html" id="toolbar">
      <div class="layui-btn-container">
        <div class="layui-row layui-col-space10">
            <div class="layui-col-xs2 layui-col-sm2 layui-col-md3">
                   <button class="layui-btn" id="addDeps"><i class="layui-icon"></i>添加一级部门</button>
            </div>
            <div class="layui-col-xs3 layui-col-sm2 layui-col-md2">
                <a class="layui-btn layui-btn-xs addChildDep" id="addChildDep">添加子部门</a>
            </div>
            <div class="layui-col-xs5 layui-col-sm6 layui-col-md5">
                <form class="layui-form layui-row layui-col-space5">
                    <div class="layui-col-xs6 layui-col-sm8 layui-col-md10">
                             <input type="text" name="username" id="depName" value="<?php echo $depName;   ?>" placeholder="请输入部门名" autocomplete="off" class="layui-input"></div>

                    <div class="layui-col-xs2 layui-col-sm2 layui-col-md2">
                         <button class="layui-btn" lay-submit="" id="searchDep" lay-filter="sreach">
                                <i class="layui-icon">&#xe615;</i></button>
                    </div>
                </form>
            </div>
            <div class="layui-col-xs2 layui-col-sm2 layui-col-md2">
                <button class="layui-btn layui-btn-sm" lay-event="refresh">刷新</button>
            </div>
        </div>
    </div>
</script>
    <script type="text/javascript">
        $(function(){
            $('body').on('click','#addDeps',function(){
                var unitId = $('#hasUnitId').val();
                if(unitId == ''){
                    layer.msg('请选择一个单位进行添加');return false;
                }else{
                    xadmin.open('添加部门','<?=\yii\helpers\Url::toRoute(['dep/dep-edit'])?>&unit_id='+unitId,500,260);
                }
            });
        });
    </script>
<script type="text/html" id="checkboxTpl">
    <input type="checkbox" name="lock" value="{{d.depid}}" title="启用" lay-filter="lockDemo" {{ d.d_status == 10 ? 'checked' : '' }}>
</script>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="authSet">权限设置</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
    <script type="text/html" id="memberBarDemo">
         {{# if(d.dep_isleader == 1){ }}
         <a class="layui-btn layui-btn-danger layui-btn-xs"  lay-event="userSetLeader" data-userId="{{ d.user_id }}" data-type="cancel">取消领导</a>
         {{#  } else { }}
        <a class="layui-btn layui-btn-warm layui-btn-xs"   lay-event="userSetLeader" data-userId="{{ d.user_id }}" data-type="set">设为领导</a>
         {{#  } }}
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
    });
</script>
<?php
$getDepList = \yii\helpers\Url::toRoute(['get-dep-list']);
$getMemberList = \yii\helpers\Url::toRoute(['dep/get-member-list']);
$update = \yii\helpers\Url::toRoute(['dep/update-dep-status']);
$delUrl = \yii\helpers\Url::toRoute(['dep/del-dep']);
$addDep = \yii\helpers\Url::toRoute(['dep/add-dep']);
$depAuth = \yii\helpers\Url::toRoute(['dep/dep-auth']);
$batchImport = \yii\helpers\Url::toRoute(['dep/batch-port']);
$batchExport = \yii\helpers\Url::toRoute(['dep/batch-port']);
$depSearch = \yii\helpers\Url::toRoute(['dep/dep-list']);
$addChildDep = \yii\helpers\Url::toRoute(['dep/dep-edit']);
$addDepLeader = \yii\helpers\Url::toRoute(['dep/set-leader']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$tableJs = <<<JS
    function rowDel(obj,data,index) {
            if(data.length == 0){
                layer.msg('请选择要删除的数据', {icon: icon.ICON_WARING});
                return false;
            }
            var depid = (data.length > 1) ? getColumn(data,'depid') : data.depid;
            layer.confirm('真的删除吗？', function(index){
                Cajax({
                    type:"POST",
                    url:"$delUrl",
                    data:{_csrfBackend:'$_csrfBackend',ids:depid}
                },function(){},function(JsonData){
                    layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
                    if(JsonData.status){
                        obj.del();
                        //location.href = location.href;
                        layer.close(index);
                    }
                });
            });
       }
    layui.use('table',function() {
            var table = layui.table,
                form = layui.form;
            var depId = $('#currentDepid').val();
            var depName = $('#depName').val();
            var renderOpt = {
                id:'depList',
                elem:'#depList',
                toolbar:"#toolbar",
                url: '$getDepList', //数据接口
                method:"POST", //
                where:{_csrfBackend:'$_csrfBackend',depId:depId,depName:depName},
                page: true, //开启分页
                limit: $pageSize,
                limits: $limitsJson,
                cols: [[
                    {field: 'depid', checkbox:true,title: 'ID', width:100, sort: true, fixed: 'left'},
                    {field: 'name', title: '部门名称', minWidth:100, sort: true},
                    {field: 'description', title: '部门描述', minWidth:100, sort: true},
                    {field: 'created_at', title: '创建时间', width:100, sort: true},
                    {field: 'updated_at', title: '更新时间', width:100, sort: true},
                    {field: 'd_status', title: '状态', templet: '#checkboxTpl',width:100,sort: true},
                    {fixed: 'right', title:'操作', toolbar: '#barDemo', width:210}
                ]]
            };
            table.render(renderOpt);
            //监听单元格编辑
            table.on('edit(depList)',
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
            table.on('toolbar(depList)',
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
                            table.reload('depList', {
                                page: {
                                    curr: 1 //重新从第 1 页开始
                                }
                            }, 'data');
                            break;
                    };
                });
            //监听行工具事件
            table.on('tool(depList)', function(obj){
                console.log(obj);
                var data = obj.data;
                if(obj.event === 'del'){
                    rowDel(obj,data);
                } else if(obj.event === 'edit'){
                    xadmin.open('部门信息修改','$addDep&depId='+data.depid,500,340);
                } else if(obj.event === 'member_stop'){
                    member_stop(obj);
                } else if(obj.event === 'authSet'){
                    xadmin.open('部门权限管理','$depAuth&description='+data.name+'&authId='+data.auth_item_id,500,340);
                }
            });
            $(function(){
               $('body').on('click','#batchImport',function(){
                   var depId = $('#currentDepid').val();
                   var unitId = $('#hasUnitId').val();
                   if(depId ==''){
                       layer.msg('请选择要移入的部门'); return false;
                   }
                  xadmin.open('批量移入人员','$batchImport&type=batchImport&unitId='+unitId+'&depId='+depId,850,600);
               }); 
               $('body').on('click','#batchExport',function(){
                   var depId = $('#currentDepid').val();
                   if(depId ==''){
                       layer.msg('请选择要移出的部门'); return false;
                   }
                   xadmin.open('批量移出人员','$batchExport&type=batchExport&depId='+depId,850,600);
               });
               $('body').on('click','#addChildDep',function(){
                   var depId = $('#currentDepid').val();
                   var unidId = $('#hasUnitId').val();
                   if(depId =='' || depId == 1 || unidId ==''){
                       layer.msg('请选择父级部门'); return false;
                   }
                   xadmin.open('添加子部门','$addChildDep&depId='+depId+'&unit_id='+unidId,500,280);
               });
            });
            form.on('checkbox(lockDemo)', function(obj){
                var checked = (obj.elem.checked == true) ? 1 : 0;
                Cajax({
                    type:"POST",
                    url:"$update",
                    data:{_csrfBackend:'$_csrfBackend',ids:this.value,checked:checked}
                },function () {},function(JsonData){
                    layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
                });
            });
            $(function(){
               $('body').on('click','#searchDep',function(){
                   var depName = $('#depName').val();
                   /*if(depName ==''){
                       layer.msg('请输入要查询的部门名称');return false;
                   }*/
                      table.reload('depList', {
                                page: {
                                    curr: 1 //重新从第 1 页开始
                                },
                                where:{
                                    depName:depName
                                }
                            }, 'data');
                   return false;
               }); 
            });
        });
//memberList  调取数据  table
layui.use('table',function() {
            var table = layui.table,
                form = layui.form,depId = $('#currentDepid').val();
            var isSuper = $('#isSuper').val();
            var renderOpt = {
                id:'memberList',
                elem:'#memberList',
                url: '$getMemberList', //数据接口
                method:"POST", //
                where:{_csrfBackend:'$_csrfBackend',depId:depId},
                page: true, //开启分页
                limit: $pageSize,
                limits: $limitsJson,
                cols: [[
                    {field: 'username', title: '昵称', minWidth:80, sort: true,templet: '#usernameTpl'},
                    {field: 'real_name', title: '真实姓名', minWidth:100, sort: true},
                    {field: 'email', title: '邮箱', width:200, sort: true},
                    {field: 'phone', title: '手机号', width:150, sort: true},
                    {field: 'created_at', title: '创建时间', width:200, sort: true}
                ]]
            };
            if(isSuper){
                renderOpt.cols[0].push({fixed: 'right', title:'操作', toolbar: '#memberBarDemo', width:100});
            }
            table.render(renderOpt);
            //监听行工具事件
            table.on('tool(memberList)', function(obj){
                console.log(obj);
                var data = obj.data;
                if(obj.event === 'userSetLeader'){
                     var userId = data.user_id,type=$(this).attr('data-type'),username=data.username;
                   layer.confirm('确定执行吗？', function(index){
                     Cajax({
                            type:"POST",
                            url:'$addDepLeader',
                            data:{_csrfBackend:'$_csrfBackend',userId:userId,type:type}
                        },function () {},function(JsonData){
                            var ic = (JsonData.status == true) ? icon.ICON_OK : icon.ICON_ERROR;
                            layer.msg(JsonData.msg,{icon:ic},function () {
                                table.reload('memberList', {
                                  url: '$getMemberList'
                                  ,where: {_csrfBackend:'$_csrfBackend',depId:depId}
                                });
                            });
                        });
                      });
                }
            });
            });
        
JS;
$this->registerJs($tableJs,\yii\web\View::POS_END);
?>