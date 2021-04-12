<?php
$params=$class->params(); 
if($class->auth){
    $params['token']=[
        'field'=>'token',
        'type'=>'string',
        'validate'=>'required',
        'description'=>'登陆颁发的token',
    ];
}
$refs=$class->ref();
 
// ksort($params);
?>
<ol class="breadcrumb">
  <li><i class='glyphicon glyphicon-home'></i> <a href="<?=\yii\helpers\Url::to(['doc/index'])?>">Overview</a></li>
  <li><a href="#"><?=$v?></a></li>
  <li><a href="<?=\yii\helpers\Url::to(['doc/group','g'=>$g,'v'=>$v])?>"><?=$gname?></a></li>
  <li class="active"><?=$class->apiDescription?> <?=$method?></li>
</ol>



<h3>请求地址</h3>
<p class=apiurl ><label class="label label-warning"><?=$class->verbs?></label> <?=$apiUrl?> 
 
<a href="<?=\yii\helpers\Url::to(['doc/test','method'=>$method,'g'=>$g,'v'=>$v])?>" class='btn btn-success btn-xs'>测试工具>></a>

 
</p>

<?php if($refs['marks']):?>
<h3>备注说明</h3>
    <div class="alert alert-warning">
        <?php foreach($refs['marks'] as $item):?> 
        <p> <?=$item?></p>
        <?php endforeach?>
    </div>
<?php endif?>

<h3>输入参数</h3>
<table class="table table-striped table-bordered table-condensed">
    <tr>
        <th>字段</th>
        <th>数据类型</th>
        <th>验证条件 <a href="<?=\yii\helpers\Url::to(['doc/index'])?>#validate">？</a></th>
        <th style='width:300px'>描述</th>
        <th style='width:30%'>示例值</th>
    </tr>
    
    <?php foreach($params as $field=>$val):?>    

    <tr class=tr-v >   
        <td><code><?=$field?></code></td>
        <td><?=$val['type']?:'string'?></td>
        <td>
        <?php           
            $sp='';
            if(is_string($val['validate'])){
                echo $sp.$val['validate'];
            }elseif(is_array($val['validate'])){
                foreach($val['validate'] as $k=>$v){         
                    if(is_numeric($k)) echo $sp.$v;
                    elseif(is_array($v)){
                        $title=json_encode($v,JSON_UNESCAPED_UNICODE);
                        echo $sp."<span title='$title'   >$k</span>";
                    }
                    $sp=',';
                }
            }
           
         ?>
        </td>
        <td><?=$val['description']?></td> 
        <td><?=$val['demo']?></td>
    </tr>
    <?php endforeach?>
</table>
<h3>返回字段</h3>
<table class="table table-striped table-bordered table-condensed" style='width:70%'>
    <tr>
        <th>字段</th>
        <th>返回类型</th>
        <th>说明</th>  
    </tr>    
    <?php foreach($refs['returns'] as $k=>$val):?>
    <tr>   
        <td><code><?=$val['field']?></code></td>
        <td><?=$val['type']?></td>
        <td><?=$val['description']?></td>
    </tr>
    <?php endforeach?>
</table>
<h3>返回结果示例JSON</h3>
<pre><code  id=well ><?=$class->returnJson()?></code></pre>
 
 
<style>
.tr-v span{text-decoration:underline}
table td {word-break: break-all;}
</style>
<script>
$(function(){
    var json=<?=$class->returnJson()?>; 
    var str = JSON.stringify(json, undefined, 2);
    document.getElementById('well').innerHTML = str; 

  
}) 
</script> 