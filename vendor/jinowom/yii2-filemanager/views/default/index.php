<?php

use yii\helpers\Html;
use jinowom\filemanager\Module;
use jinowom\filemanager\assets\FilemanagerAsset;

/* @var $this yii\web\View */

$this->title = Module::t('main', 'File manager');
$this->params['breadcrumbs'][] = $this->title;

$assetPath = FilemanagerAsset::register($this)->baseUrl;
?>

<!-- 面包屑 -->
<?= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')?>
<!-- 面包屑 -->
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                 <div class="layui-card-body layui-table-body layui-table-main">
                     
<div class="filemanager-default-index">
    <h5><?= Module::t('main', 'File manager module'); ?></h5>

    <div class="row">
        <div class="col-md-6">

            <div class="text-center">
                <h5>
                    <?= Html::a(Module::t('main', 'Files'), ['file/index']) ?>
                </h5>
                <?= Html::a(
                    Html::img($assetPath . '/images/files.png', ['alt' => 'Files'])
                    , ['file/index']
                ) ?>
            </div>
        </div>

        <div class="col-md-6">

            <div class="text-center">
                <h5>
                    <?= Html::a(Module::t('main', 'Settings'), ['default/settings']) ?>
                </h5>
                <?= Html::a(
                    Html::img($assetPath . '/images/settings.png', ['alt' => 'Tools'])
                    , ['default/settings']
                ) ?>
            </div>
        </div>
    </div>
</div>
                     
                 </div>
            
            </div>
        </div>
    </div>
</div> 