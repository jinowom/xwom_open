<?php
/**
 * @author Lujie.Zhou(gao_lujie@live.cn)
 * @Date 10/16/2014
 * @Time 10:32 AM
 */

return [
    'yigou_page' => [     //module name, if module is app, set the app id
        'label' => Yii::t('yigou_page', 'Page'),
        'sort' => 3000,
        'groups' => [
            'helperPage' => [
                'label' => Yii::t('yigou_page', 'Helper Page'),
                'sort' => 100,
                'permissions' => [
                    'index' => [    //action name
                        'label' => Yii::t('yigou_page', 'Manager'),
                        'sort' => 10,
                        'permissionKeys' => ['treemanager_node_manage'],
                    ],
                ]
            ],
            'page' => [     //controller name
                'label' => Yii::t('yigou_page', 'Page'),
                'sort' => 200,
                'permissions' => [
                    'index' => [    //action name
                        'label' => Yii::t('yigou_page', 'List'),
                        'sort' => 10,
                    ],
                    'edit' => [
                        'label' => Yii::t('yigou_page', 'Edit'),
                        'sort' => 20,
                        'permissionKeys' => ['yigou_page_page_create', 'yigou_page_page_update']
                    ],
                    'delete' => [
                        'label' => Yii::t('yigou_page', 'Delete'),
                        'sort' => 30,
                    ],
                ]
            ],
            'pageCategory' => [     //controller name
                'label' => Yii::t('yigou_page', 'Page Category'),
                'sort' => 150,
                'permissions' => [
                    'index' => [    //action name
                        'label' => Yii::t('yigou_page', 'Manager'),
                        'sort' => 10,
                        'permissionKeys' => ['treemanager_node_manage'],
                    ],
                ]
            ],
        ]
    ],
];