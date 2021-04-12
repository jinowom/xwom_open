<?php

/* @var $this yii\web\View */

$this->title = '菜单管理';
?>
<!-- 顶部菜单开始 -->
<?= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')?>
<link rel="stylesheet" href="<?=\Yii::$app->urlManager->baseUrl?>/lib/layui/lay/modules/treetable-lay/treetable.css">
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
<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
        <button class="layui-btn" onclick="xadmin.open('添加栏目','<?=\yii\helpers\Url::toRoute(['auth/add-parent-menu'])?>',500,500)"><i class="layui-icon"></i>添加</button>
        <button class="layui-btn" lay-event="hid">批量隐藏</button>
        <button class="layui-btn" lay-event="show">批量显示</button>
    </div>
</script>
<!-- 操作列 -->
<script type="text/html" id="auth_state">
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="addChild">添加子菜单</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    <!--{{#  if(d.isMenu == 1 && d.parentId ==0){ }}
    {{#  } else { }}
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    {{#  } }}-->
</script>
<script type="text/html" id="switchTpl">
  <input type="checkbox" name="status" value="{{d.status}}" selfid="{{d.authorityId}}" lay-skin="switch" lay-text="显示|不显示" lay-filter="status" {{ d.status == 1 ? 'checked' : '' }}>
</script>
<?php
$url = \yii\helpers\Url::toRoute(['auth/get-menu-list']);
$delUrl = \yii\helpers\Url::toRoute(['auth/del-menu']);
$addUrl = \yii\helpers\Url::toRoute(['auth/add-menu']);
$updateStatus = \yii\helpers\Url::toRoute(['auth/update-status']);
$updateStatusAll = \yii\helpers\Url::toRoute(['auth/update-status-all']);
$updateUrl = \yii\helpers\Url::toRoute(['auth/update-menu']);
$base = \Yii::$app->urlManager->baseUrl.'/lib/layui/lay/modules/';
$_csrfBackend = \Yii::$app->request->csrfToken;
$js = <<<JS
    layui.config({
        base: '$base'
    }).extend({
        treetable: "treetable-lay/treetable"
    }).use(['treetable'], function () {
        var $ = layui.jquery;
        var table = layui.table;
        var form = layui.form;
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
            toolbar: '#toolbarDemo',
            treeDefaultClose: true,
            treeLinkage: false,	
            cellMinWidth:10,
            cols: [[
                {type: 'checkbox', fixed: 'left'},
                {field: 'authorityName', minWidth: 150, title: '权限名称'},
                {field: 'authority', align: 'center', edit: 'text', title: '权限标识'},
                {field: 'createTime', align: 'center', title: '创建时间'},
                {field: 'orderNumber', align: 'center', title: '排序号'},
                {
                    field: 'isMenu', align: 'center', templet: function (d) {
                        if (d.isMenu == 1 && d.parentId ==0) {
                            return '<span class="layui-badge layui-bg-blue">顶级菜单</span>';
                        }else if(d.isMenu == 1){
                            return '<span class="layui-badge-rim">菜单</span>';
                        }else{
                            return '<span class="layui-badge-rim">权限</span>';
                        }
                    }, title: '类型'
                },
                {field:'status', title:'是否显示', templet: '#switchTpl', unresize: true}, 
                {toolbar:"#auth_state",width: 250, align: 'center', title: '操作'}
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
                xadmin.open('修改菜单','$addUrl?name='+data.authorityId,500,550)
            } else if(obj.event === 'addChild'){
                xadmin.open('添加菜单','$addUrl?pName='+data.authorityId,500,550)
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

            //监听属性操作
        form.on('switch(status)', function(obj){
            var name = $(this).attr('selfid')
            if(this.value == 1){
                var status = 2
            }else{
                var status =1
            }
            var index = layer.load('修改中',1, {shade: false, offset: '300px'});
            $.post("$updateStatus",{name:name,status:status},function(res){
                layer.close(index);
                if(res.code==200){
                    layer.msg('修改成功', {
                        time: 2000,//3s后自动关闭
                    },function(){
                        // window.location.reload()
                    });
                }else{
                    layer.msg("修改失败"+res.message, {
                        icon: 5 ,
                        offset: 't',
                        time: 3000,//3s后自动关闭
                    },function () {
                        // $(".layui-laypage-btn").click();
                    });
                }
            })
        });

             //头工具栏事件
        table.on('toolbar(table)', function(obj){
            var checkStatus = table.checkStatus(obj.config.id);
            var data = checkStatus.data;
            var str = ""
            $.each(data,function(i,val){
                str+=','+val.authorityId
            })
            switch(obj.event){
            case 'show':
                layer.confirm('确定设为显示吗',{offset: 't'}, function(index){
                        var index = layer.load('设置中',1, {shade: false, offset: '300px'});
                        $.post("$updateStatusAll", {name:str,status:1}, function(res){
                        layer.close(index);
                        if(res.code==200){
                            layer.msg('成功', {
                                time: 2000,//3s后自动关闭
                            },function(){
                                window.location.reload()
                            });
                            }else{
                                layer.alert("失败"+res.message, {icon: 5 ,offset: 't'});
                            }
                        })
                    });
              break;
              case 'hid':
                layer.confirm('确定设为隐藏吗',{offset: 't'}, function(index){
                        var index = layer.load('设置中',1, {shade: false, offset: '300px'});
                        $.post("$updateStatusAll", {name:str,status:0}, function(res){
                        layer.close(index);
                        if(res.code==200){
                            layer.msg('成功', {
                                time: 2000,//3s后自动关闭
                            },function(){
                                window.location.reload()
                            });
                            }else{
                                layer.alert("失败"+res.message, {icon: 5 ,offset: 't'});
                            }
                        })
                    });
              break;
            }
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