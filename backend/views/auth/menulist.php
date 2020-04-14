<?php

/* @var $this yii\web\View */

$this->title = '菜单管理';
?>
<!-- 顶部菜单开始 -->
<?= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')?>
<!-- 顶部菜单结束 -->
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-sm12 layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <table id="authTable" class="layui-table" lay-filter="table"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 操作列 -->
<script type="text/html" id="auth_state">
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="addChild">添加子菜单</a>
    {{#  if(d.isMenu == 1 && d.parentId ==0){ }}
    {{#  } else { }}
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    {{#  } }}
</script>
<?php
$url = \yii\helpers\Url::toRoute(['auth/get-menu-list']);
$delUrl = \yii\helpers\Url::toRoute(['auth/del-menu']);
$addUrl = \yii\helpers\Url::toRoute(['auth/add-menu']);
$updateUrl = \yii\helpers\Url::toRoute(['auth/update-menu']);
$_csrfBackend = \Yii::$app->request->csrfToken;
$js = <<<JS
    layui.config({
        base: 'lib/layui/lay/modules/'
    }).extend({
        treetable: 'treetable-lay/treetable'
    }).use(['treetable'], function () {
        var $ = layui.jquery;
        var table = layui.table;
        var treetable = layui.treetable;
        // 渲染表格
        treetable.render({
            treeColIndex: 1,
            treeSpid: 0,
            treeIdName: 'authorityId',
            treePidName: 'parentId',
            id:"authTable",
            elem: '#authTable',
            url: '$url',
            page: true,
            treeDefaultClose: true,
            treeLinkage: false,	
            cellMinWidth:10,
            cols: [[
                {type: 'numbers', fixed:"left", width:80, title:"序号"},
                {field: 'authorityName', minWidth: 150,width:250, title: '权限名称'},
                {field: 'authority', width:220, align: 'center', edit: 'text', title: '权限标识'},
                {field: 'createTime', width: 220,align: 'center', title: '创建时间'},
                {field: 'orderNumber', width: 120,align: 'center', title: '排序号'},
                {
                    field: 'isMenu', width: 150, align: 'center', templet: function (d) {
                        if (d.isMenu == 1 && d.parentId ==0) {
                            return '<span class="layui-badge layui-bg-blue">顶级菜单</span>';
                        }else if(d.isMenu == 1){
                            return '<span class="layui-badge-rim">菜单</span>';
                        }else{
                            return '<span class="layui-badge-rim">权限</span>';
                        }
                    }, title: '类型'
                },
                {toolbar:"#auth_state",width: 250, align: 'center',fixed:"right", title: '操作'}
            ]],
            done: function () {
                layer.closeAll('loading');
            }
        });

        table.on('tool(table)', function(obj){
            var data = obj.data;
            console.log(data)
            if(obj.event === 'del'){
                rowDel(obj,data);
            } else if(obj.event === 'edit'){
                xadmin.open('修改菜单','$addUrl&name='+data.authorityId,500,550)
            } else if(obj.event === 'addChild'){
                xadmin.open('添加菜单','$addUrl&pName='+data.authorityId,500,550)
            }
        });
        table.on('edit(table)', function(obj){
            console.log(obj);
            var val = obj.value,ids = obj.data.authorityId;
            layer.confirm('确定修改吗？', function(index){
                Cajax({
                    type:"POST",
                    url:"$updateUrl",
                    data:{_csrfBackend:'$_csrfBackend',ids:ids,name:val}
                },function(){
                    layer.closeAll();
                    load = layer.load();
                },function(JsonData){
                    layer.closeAll();
                    layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
                    if(JsonData.status){
                        obj.update({'authorityId':val,'authority':val});
                        layer.close(index);
                    }else{
                        obj.update({'authority':ids});
                    }
                })
            },function(index){
                obj.update({'authority':ids});
            });
        });
        $('#btn-expand').click(function () {
            treetable.expandAll('#auth-table');
        });

        $('#btn-fold').click(function () {
            treetable.foldAll('#auth-table');
        });
        
        function rowDel(obj,data,index) {
            if(data.length == 0){
                    layer.msg('请选择要删除的数据', {icon: icon.ICON_WARING});
                return false;
            }
            var ids = (data.length > 1) ? getColumn(data,'authorityId') : data.authorityId;
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
    });
JS;
$this->registerJs($js,\yii\web\View::POS_END)

?>