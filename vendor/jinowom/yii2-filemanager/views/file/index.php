<?php

use jinowom\filemanager\Module;
use jinowom\filemanager\assets\ModalAsset;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Module::t('main', 'Files');
$this->params['breadcrumbs'][] = ['label' => Module::t('main', 'File manager'), 'url' => ['default/index']];
//$this->params['breadcrumbs'][] = $this->title;

ModalAsset::register($this);
?>
<!-- 面包屑 -->
<?= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')?>
<!-- 面包屑 -->
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                 <div class="layui-card-body layui-table-body layui-table-main">
              
                    <iframe src="<?= Url::to(['file/filemanager']) ?>" id="post-original_thumbnail-frame" frameborder="0" role="filemanager-frame"></iframe>

                 </div>
            
            </div>
        </div>
    </div>
</div> 