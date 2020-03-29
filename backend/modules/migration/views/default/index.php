<?php
use yii\widgets\ActiveForm;
use migration\models\MigrationUtility;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\assets\AppAsset;//use migration\assets\MigrationAsset;
use backend\grid\GridView;
use backend\widgets\Pjax; 
AppAsset::register($this); 

/** @var $model MigrationUtility */
/** @var $output String */
/** @var $output_drop String */
/** @var $tables array */
/** @var ActiveForm $form */

$this->title = Yii::t('app', '全栈数据迁移');
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- 面包屑 -->
<?= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')?>
<!-- 面包屑 -->
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">                    
<?php $form = ActiveForm::begin(['id' => 'form-submit']); ?>
<div class="box box-primary">
	<div class="box-body">
	  <?= $form->field($model, 'migrationName')->textInput()?>
	  <?= $form->field($model, 'migrationPath')->textInput()?>
	  <?= $form->field($model, 'tableOption')->textInput()?>
	</div>
</div>

<?= $form->field($model, "tableSchemas")->checkboxList(MigrationUtility::getTableNames())->label("迁移表结构")->hint(Html::a("全选",'javascript:void(0)',['class'=>"select-all"]))?>
<?= $form->field($model, "tableDatas")->checkboxList(MigrationUtility::getTableNames())->label("迁移表数据")->hint(Html::a("全选",'javascript:void(0)',['class'=>"select-all"]))?>

<div class="form-group">
     <?= Html::submitButton('迁移', ['class' => 'btn btn-primary btn-block ', 'name' => 'button-submit', 'id' => 'button-submit'])?>
</div>
<?php ActiveForm::end()?>    
                            
                        </div>                       

                    </div>
                </div>
            </div>
        </div> 
<script>
$(document).ready(function() {
	$(".select-all").on("click",function() {
		var box = $(this).closest('.form-group')
		
		var selectall = box.data("selectall");
	
		if(selectall == true)
		{
			box.find("input:checkbox").prop("checked",false);  
			box.data("selectall",false);
		}
		else
		{
			box.find("input:checkbox").prop("checked",true);  
			box.data("selectall",true);
		}
	});
	
	
 
});
</script>