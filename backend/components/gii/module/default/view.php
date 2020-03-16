<?php // /* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */
echo "<?php\n";
?>

/* @var $this yii\web\View */

$this->title = '<?= $generator->moduleID . '-default-index' ?>';
$pageSize = \Yii::$app->params['pageSize'];
$limitsJson = \Yii::$app->params['limitsJson'];
<?php 
echo "?>\n";
?>
<!-- 面包屑 -->
<?php  echo "<?";?>
= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')<?php echo "?>";?>
<!-- 面包屑 -->
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5">
                        
                        <div class="<?= $generator->moduleID . '-default-index' ?>">
                            <h1><?= "<?= " ?>$this->context->action->uniqueId ?></h1>
                            <p>
                                This is the view content for action "<?= "<?= " ?>$this->context->action->id ?>".
                                The action belongs to the controller "<?= "<?= " ?>get_class($this->context) ?>"
                                in the "<?= "<?= " ?>$this->context->module->id ?>" module.
                            </p>
                            <p>
                                You may customize this page by editing the following file:<br>
                                <code><?= "<?= " ?>$this->context->action->uniqueId ?></code>
                            </p>
                        </div>
              
                    </form>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table" id="authList" lay-filter="authList"></table>
                </div>
            </div>
        </div>
    </div>
</div>

