<?php

/**
 * @file Render a round switch toggleColumn in Yii2 GridView.
 * @author Nick Denry
 */

namespace nickdenry\grid\toggle\components;

use Yii;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use nickdenry\grid\toggle\assets\RoundSwitchAsset;
use nickdenry\grid\toggle\assets\RoundSwitchThemeAsset;
use nickdenry\grid\toggle\helpers\ModelHelper;

/**
 * Render a round switch toggleColumn in Yii2 GridView.
 * @author Nick Denry
 */
class RoundSwitchColumn extends DataColumn
{
    /**
     * @var string toggle action name
     */
    public $action = 'toggle';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (empty($this->filter)) {
            $this->filter = [
                '1' => Yii::t('yii', 'Yes'),
                '0' => Yii::t('yii', 'No'),
            ];
        }
        $this->headerOptions = ArrayHelper::merge($this->headerOptions, [
            'data-toggle-action' => Url::toRoute([$this->action]),
            'data-toggle-attribute' => $this->attribute,
        ]);
        RoundSwitchAsset::register(Yii::$app->view);
        RoundSwitchThemeAsset::register(Yii::$app->view);
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $checked = ModelHelper::isChecked($model, $this->attribute);
        return Yii::$app->view->render('@nickdenry/grid/toggle/views/switch', [
            'model' => $model,
            'checked' => $checked,
            'name' => $this->attribute,
        ]);
    }
}
