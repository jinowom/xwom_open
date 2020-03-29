<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var <?= ltrim($generator->searchModelClass, '\\') ?> $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
<?php if ($generator->enablePjax): ?>
        'options' => [
            'data-pjax' => 1
        ],
<?php endif; ?>
    ]); ?>

<?php
$count = 0;
foreach ($generator->getColumnNames() as $attribute) {
    if (++$count < 6) {
        echo "    <?= " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
    } else {
        echo "    <?php // echo " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
    }
}
?>
    <!--开发调试后，请删除如上注释部分-->
    <!--用法示例，开发调试后，请删除如下 start-->
<?php
echo "    <?php  //echo $form->field($model, 'username')->textInput(['maxlength' => 20]) ?>\n\n";
echo "    <?php  //echo $form->field($model, 'password')->passwordInput(['maxlength' => 20])  ?>\n\n";
echo "    <?php  //echo $form->field($model, 'sex')->radioList(['1'=>'男','0'=>'女'])   ?>\n\n";
echo "    <?php  //echo $form->field($model, 'edu')->dropDownList(['1'=>'大学','2'=>'高中','3'=>'初中'],['prompt'=>'请选择','style'=>'width:120px'])  ?>\n\n";
echo "    <?php  //echo $form->field($model, 'file')->fileInput()  ?>\n\n";
echo "    <?php  //echo $form->field($model, 'hobby')->checkboxList(['0'=>'篮球','1'=>'足球','2'=>'羽毛球','3'=>'乒乓球'])  ?>\n\n";
echo "    <?php  //echo $form->field($model, 'info')->textarea(['rows'=>3])  ?>\n\n"; 
echo "    <?php  //echo $form->field($model, 'userid')->hiddenInput(['value'=>3])  ?>\n\n";
?>
 <!--用法示例，开发调试后，请删除如上，end-->
    <div class="form-group">
        <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('Search') ?>, ['class' => 'btn btn-primary']) ?>
        <?= "<?= " ?>Html::resetButton(<?= $generator->generateString('Reset') ?>, ['class' => 'btn btn-default']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
