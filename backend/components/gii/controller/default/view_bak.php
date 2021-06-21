<?php
/**
 * This is the template for generating an action view file.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\controller\Generator */
/* @var $action string the action ID */

echo "<?php\n";
?>

/* 
*@var $this yii\web\View 
*@author  Womtech  email:chareler@163.com
*@DateTime <?= date("Y-m-d H:i",time()) ?>
*/

$this->title = '<?= $generator->getControllerSubPath() . $generator->getControllerID() . '/' . $action ?>';
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
                        
                        <div class="<?= $generator->getControllerSubPath() . $generator->getControllerID() . '/' . $action ?>">
                            <h1><?= $generator->getControllerSubPath() . $generator->getControllerID() . '/' . $action ?></h1>

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






