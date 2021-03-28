<?php 
use common\utils\ToolUtil;
use yii\helpers\Html;
use yii\helpers\Url;
$bg_color = json_decode(getVar('BGCOLOR',2),true); 
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>">
    <meta http-equiv="Cache-Control"content="no-store" />
    <meta http-equiv=〞Pragma〞,content=〞no-cache〞>
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta http-equiv="Content-Security-Policy" content="child-src http: https:" />
    <meta http-equiv="Strict-Transport-Security" content="max-age=31536000;includeSubDomains" />
    <meta http-equiv="X-Content-Type-Options" content="nosniff" />
    <meta http-equiv="X-XSS-Protection" content="1; mode=block" />
    <meta http-equiv="Expires" content="0" />
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

            <div class="moduleBox figureStatistics" id="modelBox">
            </div>

<!--            <div class="moduleBox figureStatistics">
               <div class="titleTextBox"><span>站内短消息</span></div>
                <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                    <li class="layui-col-md2 layui-col-xs6">
                        <a href="javascript:;" class="x-admin-backlog-body">
                            <h3>文章数</h3>
                            <p>
                                <cite>66</cite></p>
                        </a>
                    </li>
                    <li class="layui-col-md2 layui-col-xs6">
                        <a href="javascript:;" class="x-admin-backlog-body">
                            <h3>会员数</h3>
                            <p>
                                <cite>12</cite></p>
                        </a>
                    </li>
                    <li class="layui-col-md2 layui-col-xs6">
                        <a href="javascript:;" class="x-admin-backlog-body">
                            <h3>回复数</h3>
                            <p>
                                <cite>99</cite></p>
                        </a>
                    </li>
                    <li class="layui-col-md2 layui-col-xs6">
                        <a href="javascript:;" class="x-admin-backlog-body">
                            <h3>商品数</h3>
                            <p>
                                <cite>67</cite></p>
                        </a>
                    </li>
                    <li class="layui-col-md2 layui-col-xs6">
                        <a href="javascript:;" class="x-admin-backlog-body">
                            <h3>文章数</h3>
                            <p>
                                <cite>67</cite></p>
                        </a>
                    </li>
                    <li class="layui-col-md2 layui-col-xs6 ">
                        <a href="javascript:;" class="x-admin-backlog-body">
                            <h3>文章数</h3>
                            <p>
                                <cite>6766</cite></p>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="moduleBox chartStatistics">
                <div class="titleTextBox"><span>站内统计</span></div>
                <div class="layui-row">
                    <div class="layui-col-md6">
                       <div id="LineChart"></div>
                    </div>
                    <div class="layui-col-md6">
                      <div id="PirChart"></div>
                    </div>
                </div>
            <div>
            </div>
        </div>-->
    </div>
    <script type="text/javascript">
    layui.use(['laydate', 'laypage','form','upload'], function(){
        var laydate = layui.laydate;
        var laypage = layui.laypage;
        var form = layui.form;
        makeLineChart()
        makePirChart()
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
            let myChart = echarts.init(document.getElementById('LineChart'), 'macarons')
            let option = {
                    xAxis: {
                        type: 'category',
                        data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [{
                        data: [820, 932, 901, 934, 1290, 1330, 1320],
                        type: 'line',
                        smooth: true
                    }]
                };
            myChart.setOption(option);
        }
        function makePirChart () {
            let myChart = echarts.init(document.getElementById('PirChart'), 'macarons')
            let option = {
                    title: {
                        text: '某站点用户访问来源',
                        subtext: '纯属虚构',
                        left: 'center'
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c} ({d}%)'
                    },
                    legend: {
                        orient: 'vertical',
                        left: 'left',
                        data: ['直接访问', '邮件营销', '联盟广告', '视频广告', '搜索引擎']
                    },
                    series: [
                        {
                            name: '访问来源',
                            type: 'pie',
                            radius: '55%',
                            center: ['50%', '60%'],
                            data: [
                                {value: 335, name: '直接访问'},
                                {value: 310, name: '邮件营销'},
                                {value: 234, name: '联盟广告'},
                                {value: 135, name: '视频广告'},
                                {value: 1548, name: '搜索引擎'}
                            ],
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
        }
    })
    </script>
</body>