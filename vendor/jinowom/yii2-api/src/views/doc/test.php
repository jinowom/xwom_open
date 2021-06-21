<?php
$params=$class->params();
$validatestring=[];
foreach($params as $field=>$rule){ 
    foreach($rule['validate'] as $key=>$value)
        $validatestring[]=is_numeric($key)?$value:$key;
}
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
  <li><a href="<?=\yii\helpers\Url::to(['doc/info','g'=>$g,'v'=>$v,'method'=>$method])?>"><?=$class->apiDescription?></a></li>
  <li class="active">测试 <?=$method?></li>
</ol>
 
<h3>请求地址</h3>
<p>
    <label class='label label-warning'><?=$class->verbs?></label>
    <span class="apiurl"><?=$apiUrl?></span>
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
<table class="table table-striped table-bordered "  >
    <tr>
        <th style='width:100px'>字段</th>
        <th>输入值</th>
        <th>数据类型</th>
        <th>验证条件</th>
        <th style='width:300px'>描述</th>     
        <th style='width:200px'>示例值</th> 
    </tr>    
    <?php foreach($params as $field=>$val):?>
    <tr class='tr-field tr-v tr-<?=$field?>' >   
        <td class='field td-<?=$field?>' ><code><?=$field?></code></td>
        <td>
            <input type="text" style='width:100%' class=input >
        </td>
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

<button class='btn btn-success btnstart'>执行请求 </button> 
 
<h3 class=returnx >执行结果</h3>
<pre><code id="return">...</code></pre>

<div class=returnurl >
    <h3>访问的Url </h3>
    <div class="well well-returnurl">
        ...
    </div>
</div>

 
<script>
$(function(){
    if($('.tr-token').length>0 ){
        $('.tr-token .input').val('<?=$_COOKIE['test_token']?>')
    }

    $('.btnstart').click(function(){
        var btnobj=$(this);
        btnobj.attr('disabled',true).append('<i class="glyphicon glyphicon-refresh"></i>');
        var data={};
        var tokenObj=null;
        $('.tr-field').each(function(k,v){
            var field=$(v).find('.field').text();
            data[field]=$(v).find('.input').val();
            if(field=='token'){
                tokenObj=v;
            }
        });
       
        var apiurl=$('.apiurl').text(); 
        var method='<?=$method?>';
        var verbs='<?=$verbs?>';
        $('.returnx label').remove();   
  
        $.ajax({
            url:'<?=\yii\helpers\Url::to(["doc/dotest"])?>',
            type:'post',
            data:{
                data,apiurl,verbs        
            },
            success:function(ret){
                btnobj.attr('disabled',false).find('i').remove();
                $('.returnx label').remove();     
                $('.returnx').append('<label class="label label-warning" title="耗时">'+ret.time+'s </label>');
                $('#return').html('请求链接:<a href='+ret.reqUrl+' target=_blank > '+ret.reqUrl+'</a><br><br>'+ret.return);

            
                var str = JSON.stringify(ret.returnJson, undefined, 2);       
                $('#return').html(str);

                $('.well-returnurl').html("<a href='"+ret.reqUrl+"' target=_blank >"+ret.reqUrl+"</a>");
            },
            error:function(ret){
                btnobj.attr('disabled',false);
                console.log(ret)
                $('.returnx label').remove();     
                $('#return').html(ret.responseText); 
            }
        });
    })
});
</script>

<style>
.label{font-size:12px}
.tr-v span{text-decoration:underline}
table td {word-break: break-all;}
</style>