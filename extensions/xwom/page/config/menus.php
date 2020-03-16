<?php
/**
 * @author Lujie.Zhou(lujie.zhou@jago-ag.cn)
 * @Date 6/6/2015
 * @Time 6:06 PM
 */

return [
    'cms' => [
        'label' => Yii::t('yigou_page', 'CMS'),
        'sort' => 3000,
        'url' => ['/yigou_page/page/index'],
        'icon' => 'fa fa-file-archive-o',
        'items' => [
            'page' => [
                'label' => Yii::t('yigou_page', 'Page'),
                'sort' => 500,
                'url' => ['/yigou_page/page/index'],
                'icon' => 'fa fa-file-archive-o',
            ],
            'pageCategory' => [
                'label' => Yii::t('yigou_page', 'Page Category'),
                'sort' => 450,
                'url' => ['/yigou_page/page-category/index'],
                'icon' => 'fa fa-file-archive-o',
            ],
            'helperPage' => [
                'label' => Yii::t('yigou_page', 'Helper Page'),
                'sort' => 200,
                'url' => ['/yigou_page/helper-page/index'],
                'icon' => 'fa fa-file-archive-o',
            ],
        ]
    ],
];

