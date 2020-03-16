### 作用是把你现有的数据库表结构和数据选择性导出生成yii2的迁移文件

1.下载模块源码解压缩到 `backend/modules/`

2.在 `backend/config/main.php` 添加如下配置

```
'modules' => [
        'migration' => [
            'class' => 'migration\Module',
        ]
    ],
'aliases' => [
        '@migration' => '@backend/modules/migration',
    ],
```

3.在你的后台访问 http://yourdomain/migration

演示地址： http://www.51siyuan.cn/admin/migration

演示账号 `demo`  密码 `111111`



命令行使用方法

1.在`console/config/main.php` 添加如下配置
```
'controllerMap' => [
        'migrate' => [
            'class' => 'backend\modules\migration\console\MigrateController',
            'useTablePrefix' => true,
            'migrationPath' => '@console/migrations',
        ],
    ],
```

2.`php yii migrate/dump`

o了
