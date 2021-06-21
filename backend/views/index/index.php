<?php
use common\utils\ToolUtil;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = Yii::$app->name.'管理控制台';
?>
<!-- 顶部开始 -->
<div class="container">
    <div class="logo">
        <a><?= Yii::$app->name; ?></a></div>
    <div class="left_open">
        <a><i title="展开左侧栏" class="iconfont">&#xe699;</i></a>
    </div>   
    <ul class="layui-nav right" lay-filter="">
        <?php foreach ($permissionsList as $key => $value) { ?>
            <?php if($key == 0 ){ ?>
                <li class="layui-nav-item to-index layui-this" >
                    <?php if(!empty($value['children'][0])){  ?>
                          <a class="leftbon" b_url="<?=\yii\helpers\Url::toRoute('index/dashboard?name='.$value['name'])?>" id="<?php echo $value['name'] ?>" ><?=$value['description']?></a>
                    <?php  }else{ ?>
                          <a class="leftbon" b_url="<?=\yii\helpers\Url::toRoute('index/dashboard?name='.$value['name'])?>" id="<?php echo $value['name'] ?>" ><?=$value['description']?></a>
                    <?php  } ?>
                </li> 
            <?php }else{ ?>
                <li class="layui-nav-item to-index">
                    <?php if(!empty($value['children'][0])){  ?>
                          <a class="leftbon" b_url="<?=\yii\helpers\Url::toRoute('index/dashboard?name='.$value['name'])?>" id="<?php echo $value['name'] ?>" ><?=$value['description']?></a>
                    <?php  }else{ ?>
                          <a class="leftbon" b_url="<?=\yii\helpers\Url::toRoute('index/dashboard?name='.$value['name'])?>" id="<?php echo $value['name'] ?>" ><?=$value['description']?></a>
                    <?php  } ?>
                </li> 
            <?php } ?>
        <?php } ?>
        
        <li class="layui-nav-item">
            <a href="javascript:;"><?=\Yii::$app->getUser()->getIdentity()->real_name;?></a>
            <dl class="layui-nav-child">
                <!-- 二级菜单 -->
                <dd>
                    <a onclick="xadmin.open('个人信息','<?=\yii\helpers\Url::toRoute(['index/update-self-data'])?>',500,450)">个人信息</a></dd>
                <dd>
                    <a onclick="xadmin.open('修改密码','<?=\yii\helpers\Url::toRoute(['index/update-pwd'])?>',500,400)">修改密码</a></dd>
                <dd>
                    <a href="<?=\yii\helpers\Url::toRoute(['site/logout'])?>">退出</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item to-index">
                <a onclick="xadmin.open('信息中心','<?=\yii\helpers\Url::toRoute(['common/config-message-map/index'])?>')">
                <i class="layui-icon layui-icon-notice"></i>
                <?php if ($mesCount !=0 ){ ?>
                    <span class="layui-badge-dot"></span>
                <?php } ?>
            </a>
        </li> 
    </ul>
</div>

<div class="left-nav">
    <div id="side-nav">
        <?php foreach ($permissionsList as $key => $value) { ?>
            <?php if($key == 0 && $value['status']==1){ ?>
                <ul id="nav" class="nav <?php echo $value['name'] ?>">
                    <?=$permissions = ToolUtil::menuListHtml($value['children'])?>
                </ul>
            <?php }else if($value['status']==1){ ?>
                <ul id="nav" class="nav <?php echo $value['name'] ?>" style="display:none;">
                    <?=$permissions = ToolUtil::menuListHtml($value['children'])?>
                </ul>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li class="home">
                <i class="layui-icon">&#xe68e;</i>首页</li></ul>
        <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
            <dl>
                <dd data-type="this">关闭当前</dd>
                <dd data-type="other">关闭其它</dd>
                <dd data-type="all">关闭全部</dd></dl>
        </div>
        <div class="layui-tab-content">
            <div id="oneTeb" class="layui-tab-item layui-show">
                <?php foreach ($permissionsList as $key => $value) { ?>
                    <?php if($key == 0 ){ ?>
                        <iframe id = "welcome" src='<?=Url::toRoute('index/dashboard?name='.$value['name'])?>' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <div id="tab_show"></div>
    </div>
</div>
<div class="page-content-bg"></div>
<style id="theme_style"></style>
<script>
layui.use('element', function(){
  var $ = layui.jquery
  ,element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块
  
  $('.leftbon').on('click',function(){
        var str = "" 
        var b_url = $(this).attr('b_url')
        var childID = $(this).attr('id')
        $('.nav').attr('style','display:none;')
        $('.'+childID+'').attr('style','')
        $('#welcome').attr('src',b_url)
        $('#oneTeb').attr('class','layui-tab-item layui-show');
    })
});
</script>
