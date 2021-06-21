<?php $bg_color = json_decode(getVar('BGCOLOR',2),true); ?>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo Yii::$app->urlManager->baseUrl ?>/css/topTypeIconfont.css">
    <script src="<?php echo Yii::$app->urlManager->baseUrl?>/js/echarts.min.js"></script>
    <style type="text/less">
        #visitorStatistics {
            @tint-font-color: rgb(234, 232, 232);
            @deep-font-color: rgb(16, 16, 16);
            padding: 10px;
            font-size: 16px;
            background: #EDEDF2;
            .statisticsBody{
                /* publish start */
                .moduleBox {
                    background: #fff;
                    margin-bottom: 10px;
                    padding: 10px;
                    border-radius: 6px;
                    > .titleTextBox {
                        color: #989898;
                        margin-bottom: 5px;
                        font-size: 12px;
                        padding: 2px 16px;
                        display: flex;
                        flex-direction: row;
                        justify-content: space-between;
                        border-left: 4px solid #FF9966;
                    }
                }
                /* publish end */
                .figureStatistics{
                    .figureStatisticsBox{
                        display: flex;
                        flex-direction: row;
                        /* justify-content: flex-start; */
                        >li{
                            width: 25%;
                            padding-right: 16px;
                            .singleStatisticsBox{
                                cursor:pointer;
                                width: 100%;
                                color: #fff;
                                background: #CC99CC;
                                display: flex;
                                flex-direction: row;
                                justify-content: space-around;
                                font-size: 14px;
                                padding: 16px 0;
                                -webkit-border-radius: 6px;
                                -moz-border-radius: 6px;
                                border-radius: 6px;
                                .statisticsBoxIcon{
                                    >span{
                                        >.layui-icon {
                                            font-size: 35px;
                                        }
                                    }
                                }
                                .textBox{
                                    .numberText{
                                        display: inline-block;
                                        margin-top: 8px;
                                        /* font-size: 12px; */
                                    }
                                }
                            }
                        }
                    }
                }
                .chartStatistics{
                    .searchBox{
                        text-align: right;
                        color: #989898;
                        font-size: 12px;
                        >.layui-inline{
                            margin-right: 20px;
                        }
                    }
                    #LineChart{
                        width: 100%;
                        height: 300px;
                    }
                }
            }
        }
    </style>
    <script src="<?php echo Yii::$app->urlManager->baseUrl ?>/js/less.2.5.3.min.js"></script>
</head>
<body>
    <div id="visitorStatistics">
        <div class="statisticsBody">
            <div class="moduleBox figureStatistics">
               <div class="titleTextBox"><span>快接入口</span></div>
                <ul class="figureStatisticsBox">
                   <?php $max = count($bg_color) ?>
                    <?php foreach ($data as $key => $value) { ?>
                        <li>
                        <?php if ($key>=$max) { ?>
                            <div class="singleStatisticsBox StatisticsBox" workName="<?=$value['name']?>">
                        <?php }else{ ?> 
                            <div class="singleStatisticsBox StatisticsBox" workName="<?=$value['name']?>" style="background: <?=$bg_color[$key]?>;" >
                        <?php } ?>
                                <div class="statisticsBoxIcon">
                                    <span ><i class="layui-icon <?=$value['icon']?>"></i></span>
                                </div>
                                <div class="textBox">
                                    <span class="numberText"><?=$value['description']?></span>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <div class="moduleBox figureStatistics" id="modelBox">
            </div>

           <div class="moduleBox figureStatistics">
               <div class="titleTextBox"><span>站内短消息</span></div>
               <table id="demo" lay-filter="test"></table>
            </div>

            <div class="moduleBox chartStatistics">
                <div class="titleTextBox"><span>站内统计</span></div>
                <div id="LineChart"></div>
            <div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    layui.use(['table', 'laydate', 'laypage', 'form', 'upload'], function(){
        var laydate = layui.laydate;
        var laypage = layui.laypage;
        var form = layui.form;
        var table = layui.table;
                //私信列表
        table.render({
            elem: '#demo'
            ,url: "<?=\yii\helpers\Url::toRoute(['common/config-message-map/self-list'])?>" //数据接口
            ,page: true //开启分页
            ,limit: 5
            ,limits: [5,10,50,100]
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
        table.on('row(test)', function(obj){
            var data = obj.data;
            var index = layer.load('修改中',1, {shade: false, offset: '300px'});
            $.get("<?=\yii\helpers\Url::toRoute(['common/config-message-map/read-one'])?>",{id:data.id},function(res){
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
        makeLineChart()
        $('.StatisticsBox').on('click',function(){
            var workName = $(this).attr('workName')
            $.get("<?=\yii\helpers\Url::toRoute('index/dashboard') ?>", {name:workName}, function(res){
                if(res.code==200){
                    //菜单不存在子集菜单的时候直接展示
                    if(res.data.length === 0){
                        layer.open({
                            type: 2,
                            offset: 't',
                            content:"<?=Yii::$app->urlManager->baseUrl?>/index.php/"+workName,
                            area:['100%','100%'],
                        });
                    }else{
                        var str = ""
                        console.log(res)
                        str+='<ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">'
                        $.each(res.data, function(i, v) {
                            str+='<li class="StatisticsChild layui-col-md2 layui-col-xs6" workName="'+v.name+'">'
                                str+='<a href="javascript:;" class="x-admin-backlog-body">'
                                    str+=' <h3>'+v.description+'</h3>'
                                str+='</a>'
                            str+=' </li>'
                        })
                        str+=' </ul>'
                        $('#modelBox').empty()
                        $('#modelBox').append(str)
                        $('.StatisticsChild').on('click',function(){
                            var workName = $(this).attr('workName')
                            layer.open({
                                type: 2,
                                offset: 't',
                                content:"<?=Yii::$app->urlManager->baseUrl?>/index.php/"+workName,
                                area:['100%','100%'],
                            });
                        })
                    }
                }else{
                    layer.msg("失败")
                }
            })
        })
        function makeLineChart () {
            $.get("<?=\yii\helpers\Url::toRoute('static/get-user-count') ?>", function(res){
                let myChart = echarts.init(document.getElementById('LineChart'), 'macarons')
                let option = {
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                                type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                            }
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis: [
                            {
                                type: 'category',
                                data: res.data.username,
                                axisTick: {
                                    alignWithLabel: true
                                }
                            }
                        ],
                        yAxis: [
                            {
                                type: 'value'
                            }
                        ],
                        series: [
                            {
                                name: '登录次数',
                                type: 'bar',
                                barWidth: '60%',
                                data: res.data.login_count
                            }
                        ]
                    };
                myChart.setOption(option); 
            })
        }
    })
    </script>
</body>