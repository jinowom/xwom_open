<?php

/**
 * Round switch view.
 * @project yii2-round-switch-column
 * @author Nick Denry
 */

use yii\helpers\Html;

?>


<label class="yii2-round-switch left">
    <?= Html::checkbox($name, $checked, [
        'data-id' => $model->id,
    ]); ?>
    <div class="slider round"></div>
</label>