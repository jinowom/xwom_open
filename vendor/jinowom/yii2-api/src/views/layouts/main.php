 <?php
 use Yii;  
 use yii\helpers\Url;  
 use jinowom\api\Config;  
$defaultVersion=Yii::$app->controller->module->defaultVersion;
$nowv=Yii::$app->request->get('v',$defaultVersion);
 
 
 ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=docice-width, initial-scale=1">    
<title><?=(Yii::$app->controller->title) ?> - <?=Yii::$app->controller->module->docTitle?></title>
<link href="//cdn.bootcss.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>  
<?php $this->head() ?>
</head>
<?=$this->render('_css')?>
<?php $this->beginBody() ?>   
<body >
<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?=Url::to(['doc/index'])?>">
            <?=Yii::$app->controller->module->docTitle?>
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Dashboard</a></li>
            <li><a href="https://github.com/jinowom/yii2-api" target=_blank >Help?</a></li>
 
            <li>  
                <select name="" id="vc" style='margin:15px 10px 0 20px' >
                    <?php foreach(Config::getAllVersions() as $v):?>                    
                        <option value="<?=$v?>" <?=$nowv==$v?'selected':''?> >版本： <?=$v?></option>
                    <?php endforeach?>
                </select>               
            </li>
 
          </ul>
          <form class="navbar-form navbar-right" action='<?=Url::to(['doc/search'])?>' method=get >
            <input type="text" class="form-control" placeholder="Search..." name=key >
          </form>
        </div>
      </div> 
    </nav>

<div class="container-fluid">    
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar navx"> 
                <li class="li-home"><a href="<?=Url::to(['doc/index','v'=>$nowv])?>">首页</a></li>
                <?php foreach(Config::getAllList() as $key=>$value):?>
                  <li class=li-<?=$key?> ><a href="<?=Url::to(['doc/group','g'=>$key,'v'=>$nowv])?>"><?=$value?></a></li>
                <?php endforeach?>
     
            </ul>
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            
            <?=$content?>
        </div>

    </div>

</div>
<?php $this->endBody() ?>
<script>
$(function(){
    $('#vc').change(function(){
        var v=($(this).val());
        var url=window.location.href;    
        if(!/v=<?=$nowv?>/.test(url)){
            url=url+'?v='+v;   
        }else{
            url=url.replace(/v=<?=$nowv?>/,'v='+v)  ;   
        }
        location=url;
    });

    var g='<?=Yii::$app->request->get('g')?>';
    if(g!=''){
        $('.navx li').removeClass('active');
        $('.navx li.li-'+g).addClass('active');
    }else{
        $('.navx li.li-home').addClass('active');
    }
});
</script>
</body>
</html> 
<?php $this->endPage() ?> 
 
