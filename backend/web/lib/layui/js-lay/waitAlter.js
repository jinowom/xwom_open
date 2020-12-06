/**
 * Created by WANGWEIHUA on 2020/3/28.
 */
layui.define(['form','layer','table','laydate'],function(exports){ //提示：模块也可以依赖其它模块，如：layui.define('layer', callback);
    var layer=layui.layer,table = layui.table, $ = layui.jquery,addUrl,laydate = layui.laydate,form = layui.form,editUrl,delUrl,csrfToken,page,pages;
    var T = {
        init:function(ele){
            var $ele = $("#"+ele);
            addUrl = $ele.data('add'); editUrl = $ele.data('edit'); delUrl = $ele.data('del'); csrfToken = $ele.data('csrf');
            page = $ele.data('page');pages = $ele.data('pages');
            this.render(ele);
            this.toolTable(ele);
            this.barTable(ele);
            this.switchForm();
            this.initSearch(ele);
        },
        render:function(ele){
            layer.load(2);
            table.render({
                id:ele,
                elem:'#'+ele,
                toolbar:"#barTool",
                url: $("#"+ele).attr('lay-url'), //数据接口
                method:"POST", //
                where:{_csrfBackend:csrfToken},
                page: true, //开启分页
                limit: page,
                limits: pages,
                loading:true,
                cols: [[
                    {field: 'id', align:'center', checkbox:true, title: 'ID', width:80, sort: true, fixed: 'left'},
                    {field: 'title',align:'center', title: '标题', minWidth:150, sort: true},
                    {field: 't_date',align:'center', title: '投稿日期',minWidth: 150, width:150,sort: true},
                    {field: 'paper',align:'center', title: '报纸版次', minWidth:150, width:280, sort: true},
                    {field: 'author',align:'center', title: '作者', minWidth:150, width:120,sort: true},
                    {field: 'newstype_id',align:'center', title: '稿件类型', minWidth:80, width:100, sort: true},
                    {field: 'status',align:'center', title: '状态', minWidth:120,width:150, sort: true,},
                    {fixed: 'right',align:'center', title:'操作', toolbar: '#tdTool', width:200}
                ]],done: function () {
                    layer.closeAll('loading');
                }
            });
        },
        //监听行事件
        toolTable:function(ele){
            var _this = this;
            table.on('tool('+ele+')',function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    _this.rowDel(obj,data);
                } else if(obj.event === 'edit'){
                    xadmin.open('编辑稿件',addUrl+"&id="+data.id,'','',true,false)
                }
            });
        },
        //头工具栏事件
        barTable:function(ele){
            var _this = this;
            table.on('toolbar('+ele+')',function(obj) {
                var checkStatus = table.checkStatus(obj.config.id),data=obj.config.data;
                switch (obj.event) {
                    case 'add':
                        xadmin.open('添加稿件',addUrl,'','',true,false);
                        break;
                    case 'delAll':
                        _this.rowDel(obj,checkStatus.data,true,ele);
                        break;
                    case 'refresh':
                        _this.render(ele);
                        //执行重载
                        tReload(table,ele,{page:{curr:1}});
                        break;
                };
            });
        },
        //监听开关切换
        switchForm:function(){
            form.on('switch(switchForm)', function(data){
                layer.msg('开关checked：'+ (this.checked ? 'true' : 'false'), {
                    offset: '6px'
                });
                layer.tips('温馨提示：请注意开关状态的文字可以随意定义，而不仅仅是ON|OFF', data.othis)
            });
        },
        //删除数据
        rowDel:function(obj,data,index,ele){
            if(data.length == 0){
                layer.msg('请选择要删除的数据', {icon: icon.ICON_WARING});
                return false;
            }
            var ids = (index == true) ? getColumn(data,'id') : data.id;
            layer.confirm('真的删除吗？', function(index){
                Cajax({
                    type:"POST",
                    url:delUrl,
                    data:{_csrfBackend:csrfToken,ids:ids}
                },function(){
                    layer.closeAll();
                    layer.load(2);
                },function(JsonData){
                    layer.closeAll();
                    layer.msg(JsonData.msg,{icon:(JsonData.status) ? icon.ICON_OK : icon.ICON_ERROR});
                    if(JsonData.status){
                        (index == true) ? tReload(table,ele,{page:{curr:1}}) : obj.del();

                    }
                })
            });
        },
        initSearch: function(ele){
            var active = {
                sreach: function(){
                    var start = $('#start'), end = $('#end'), title = $('#title'), type = $('#type'), status = $("#status");
                    //执行重载
                    table.reload(ele, {
                        page: { curr: 1 } ,where: {
                            start: start.val(), end: end.val(), title: title.val(), type: type.val(), status : status.val()
                        }
                    }, 'data');
                }
            };
            laydate.render({
                elem: '#start' //指定元素
            });
            //执行一个laydate实例
            laydate.render({
                elem: '#end' //指定元素
            });
            $('#sreach').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        }
    };
    exports('waitAlter', T);
});