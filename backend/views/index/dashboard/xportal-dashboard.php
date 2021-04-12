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
                    #LineChart{
                        width: 100%;
                        height: 300px;
                    }
                    #PirChart{
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

            <div class="moduleBox figureStatistics" id="modelBox"> </div>

            <div class="moduleBox figureStatistics">
               <div class="titleTextBox"><span>站内短消息</span></div>
                <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog" id="shoutManage"></ul>
            </div>

            <div class="moduleBox chartStatistics">
                <div class="titleTextBox"><span>站内统计</span></div>
                    <div class="layui-row">
                        <div class="layui-col-md6">
                            <br>
                            <div class="layui-input-inline">
                                <input class="layui-input" id="selTime" placeholder=" - ">
                            </div>
                            <div id="LineChart"></div>
                        </div>
                        <div class="layui-col-md6">
                            <div id="PirChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    layui.use(['laydate', 'laypage','form','upload'], function(){
        var laydate = layui.laydate;
        var laypage = layui.laypage;
        var form = layui.form;
        var two = "<?=date('Y-m-d',time()) ?>";
        var one = "<?=date('Y-m-d',time()-7*24*60*60) ?>";
        var selTime = one+" - "+two
        makeLineChart(selTime)
        makePirChart()
        getShortMessage()
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
        //获取资源库统计情况
        function getShortMessage () {
            $.get("<?=\yii\helpers\Url::toRoute('static/get-short-message') ?>", function(res){
                var str = ''
                $.each(res.data,function(k,v){
                    str+='<li class="layui-col-md2 layui-col-xs6">'
                        str+='<a href="javascript:;" class="x-admin-backlog-body">'
                            str+='<h3>'+v.label+'</h3>'
                            str+='<p>'
                                str+='<cite>'+v.newsCount+'</cite></p>'
                        str+='</a>'
                    str+='</li>'
                })
                $('#shoutManage').empty()
                $('#shoutManage').append(str)
            })
        }
        //获取七天内新闻发布情况
        function makeLineChart (value) {
            $.get("<?=\yii\helpers\Url::toRoute('static/get-news-release-count') ?>", {selTime:value}, function(res){
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
                                data: res.data.timeData,
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
                                name: '新闻发布数',
                                type: 'bar',
                                barWidth: '60%',
                                data: res.data.newsDate
                            }
                        ]
                    };
                myChart.setOption(option);  
            })
        }
        function makePirChart () {
            $.get("<?=\yii\helpers\Url::toRoute('static/get-news-count') ?>",function(res){
                let myChart = echarts.init(document.getElementById('PirChart'), 'macarons')
                let option = {
                        title: {
                            text: '资源库数据统计',
                            left: 'center'
                        },
                        tooltip: {
                            trigger: 'item',
                            formatter: '{a} <br/>{b} : {c} ({d}%)'
                        },
                        legend: {
                            orient: 'vertical',
                            left: 'left',
                            data: res.data.key
                        },
                        series: [
                            {
                                name: '资源库统计',
                                type: 'pie',
                                radius: '55%',
                                center: ['50%', '60%'],
                                data:res.data.newsType,
                                emphasis: {
                                    itemStyle: {
                                        shadowBlur: 10,
                                        shadowOffsetX: 0,
                                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                                    }
                                }
                            }
                        ]
                    };
                myChart.setOption(option);
            })
        }
          //日期范围
        laydate.render({
            elem: '#selTime'
            ,range: true
            ,value: one+" - "+two
            ,done:function(value,date){
                makeLineChart(value)
                }
        });
    })
    </script>
</body>