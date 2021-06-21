<?php use yii\helpers\Url;?>
<ol class="breadcrumb">
  <li><i class='glyphicon glyphicon-home'></i> <a href="<?=\yii\helpers\Url::to(['doc/index'])?>">Overview</a></li>
  <li class="active"><?=Yii::$app->controller->title?> , 相关接口 <?=$count?> 个</li>
</ol>
 

<table class="table table-hover">
    <tr>
        <th>方法</th>
        <th>版本</th>
        <th>描述</th> 
        <th>支持动作</th> 
        <th>是否需要认证</th>
        <th>#</th>
    </tr>
    <?php foreach($list as $item):?>
    <tr>
        <td><a href="<?=\yii\helpers\Url::to(['info','method'=>$item['method'],'v'=>$item['v'],'g'=>$item['g']])?>"><?=$item['method']?></a></td>
        <td><?=$item['v']?></td>
        <td><?=$item['apiDescription']?></td>
        <td><?=$item['verbs']?></td>
        <td><?=$item['auth']?'true':'false'?></td>
        <td>
            <a href="<?=Url::to(['doc/test','method'=>$item['method'],'v'=>$item['v'],'g'=>$item['g']])?>">测试</a> |
            <a href="<?=Url::to(['doc/info','method'=>$item['method'],'v'=>$item['v'],'g'=>$item['g']])?>#params">详细参数</a> |
            <a href="<?=Url::to(['doc/info','method'=>$item['method'],'v'=>$item['v'],'g'=>$item['g']])?>#return">返回字段</a> |
            <a href="<?=Url::to(['doc/info','method'=>$item['method'],'v'=>$item['v'],'g'=>$item['g']])?>#demo">示例结果</a>  
        </td>
    </tr>
    <?php endforeach?>
</table>