<?php
/**
 * @var yii\web\View $this
 * @var jinostart\workflow\manager\models\Workflow $model
 */

use yii\helpers\Html;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('workflow', 'Workflow'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('workflow', 'Update');
?>
<div class="workflow-workflow-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>
