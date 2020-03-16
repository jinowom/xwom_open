<?php
/**
 * @var yii\web\View $this
 * @var jinostart\workflow\manager\models\Workflow $model
 */

use yii\helpers\Html;

$this->title = Yii::t('workflow', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '系统管理'), 'url' => ['/system-index/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('workflow', 'Workflow'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workflow-default-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>
