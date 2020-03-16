<?php
/**
 * @author Lujie.Zhou(lujie.zhou@jago-ag.cn)
 * @Date 3/28/2016
 * @Time 3:42 PM
 */

use kartik\tree\TreeView;
use kiwi\Kiwi;

$this->title = Yii::t('yigou_page', 'Page Category');

$pageCategoryClass = Kiwi::getPageCategoryClass();

echo TreeView::widget([
    // single query fetch to render the tree
    // use the Product model you have in the previous step
    'query' => $pageCategoryClass::find()->addOrderBy('root, lft'),
    'headingOptions' => ['label' => Yii::t('yigou_page', 'Page Category')],
    'fontAwesome' => false,     // optional
    'isAdmin' => false,         // optional (toggle to enable admin mode)
    'displayValue' => 1,        // initial display value
    'softDelete' => true,       // defaults to true
    'cacheSettings' => [
        'enableCache' => true   // defaults to true
    ],
    'iconEditSettings' => [
        'show' => 'none'
    ],
    'nodeAddlViews' => [
        '2' => '@yigou/page/views/backend/page-category/_form.php',
    ],
]);