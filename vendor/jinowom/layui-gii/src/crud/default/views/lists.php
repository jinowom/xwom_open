<div class="header">
    <blockquote class="layui-elem-quote quoteBox">
        <form class="layui-form" id="searchForm">
            <div class="layui-inline">
                <div class="layui-input-inline">
                    <input type="text"  name="key" class="layui-input" placeholder="请输入搜索的内容" />
                </div>
            </div>
            <div class="layui-inline">
                <a class="layui-btn searchBtn" data-type="reload">搜索</a>
            </div>
            <div class="layui-inline">
                <button class="layui-btn layui-btn-normal" type="reset">重置</button>
            </div>
            <div class="layui-inline">
                <a class="layui-btn layui-btn-normal addBtn">添加</a>
            </div>
        </form>
    </blockquote>
    <table id="list" lay-filter="list"></table>
    <!--操作-->
    <script type="text/html" id="listBar">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>
    </script>
</div>

<script type="text/javascript">

    layui.use(['form','layer','laydate','table','laytpl'],function(){
        var form = layui.form,
            layer = parent.layer === undefined ? layui.layer : top.layer,
            $ = layui.jquery,
            table = layui.table;

        //列表
        var tableIns = table.render({
            elem: '#list',
            url : 'lists',
            where: searchWhere(),
            cellMinWidth : 95,
            page : true,
            height : "full-125",
            limit : 20,
            limits : [10,15,20,25],
            id : "list",
            cols : [[
                <?php
                $count = 0;
                $model = new $generator->modelClass();
                foreach ($generator->getColumnNames() as $name) {
                    $label = $model->getAttributeLabel($name);
                    if(substr($name, -5) == '_time'){
                        $item = <<<ITEM
                {field: '$name', title: '$label', width: '', align:'center', minWidth:110, templet:function(d){
                        if(d.$name){
                            return Format(d.$name, 'yyyy-MM-dd hh:mm');
                        }
                    }},          
ITEM;
                    }else{
                        $item = <<<ITEM
                {field: '$name', title: '$label', width: '', align:'center'},            
ITEM;
                    }

                    ++$count;
                    $item .= "\n";
                    echo $item;
                }
                ?>
                {title: '操作', width:180, templet:'#listBar',fixed:"right", align:"center"}
            ]],
            response: {statusCode: API_CODE.SUCCESS},
            parseData: function (res) {
                return parseTableData(res)
            }
        });
        //搜索【此功能需要后台配合，所以暂时没有动态效果演示】
        $(".searchBtn").on("click",function(){
            table.reload("list",{
                page: {
                    curr: 1 //重新从第 1 页开始
                },
                where: searchWhere()
            })
        });
        function searchWhere(){
            var arr = $('#searchForm').serializeArray();
            let where = [];
            arr.forEach((item, index) => {
                where[item.name] = item.value
            })
            return where;
        }

        //添加
        function add(){
            var index = layui.layer.open({
                title : "添加",
                type : 2,
                area: DEFAULT_DIALOG_MID_AREA,
                content : "create",
                success : function(layero, index){
                    var body = layui.layer.getChildFrame('body', index);
                    setTimeout(function(){
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    },500)
                },
                end: function () {
                    tableIns.reload()
                }
            })
        }

        //修改
        function edit(id){
            var index = layui.layer.open({
                title : "修改",
                type : 2,
                area: DEFAULT_DIALOG_MID_AREA,
                content : "edit?id="+id,
                success : function(layero, index){
                    var body = layui.layer.getChildFrame('body', index);
                    setTimeout(function(){
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    },500)
                },
                end: function () {
                    tableIns.reload()
                }
            })
        }
        $(".addBtn").click(function(){
            add();
        })
        //列表操作
        table.on('tool(list)', function(obj){
            var layEvent = obj.event,
                data = obj.data,
                id = data.id;
            if(layEvent === 'edit'){ //编辑
                edit(id);
            } else if(layEvent === 'del'){ //删除
                layer.confirm('确定删除操作？',{icon:3, title:'提示信息'},function(index){
                    $.post("delete",{id : id },function(data){
                        if(data.code == API_CODE.SUCCESS){
                            tableIns.reload();
                        }else{
                            layer.alert(data.msg)
                        }
                        layer.close(index);
                    })
                });
            }
        });

    })

</script>