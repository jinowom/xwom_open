## YII2 Xwom System
------------
xwom是基于Yii2的综合管理业务快速开发引擎。本引擎采用YII V2.032搭建，其运行环境与yii2(php>=5.4)一致。xwom旨在为yii2爱好者提供一个基础功能稳定完善的系统，使开发者更专注于业务功能开发。xwom没有对yii2做任何的修改、封装，但是把yii2的一些优秀特性几乎都用在了xwom上， 但xwom提倡简洁、快速上手，基于xwom开发可以无需文档，可以在此基础开发其实际应用，譬如：cms内容管理系统、商城、ERP、新闻采编等。

一、更新记录Records
------------

二、帮助Help
------------

三、功能
------------

四、快速体验Experience
------------


五、安装 Installation
------------
1、composer install
2、依次执行以下命令初始化yii2框架以及导入数据库
```php
$ cd webApp
$ php ./init --env=Development #初始化yii2框架，线上环境请使用--env=Production
$ php ./yii migrate/up --interactive=0 #导入迁移备份数据库，执行此步骤之前请先到common/config/main-local.php修改成正确的数据库配置

```

六## 配置Configuration

```php



```
七、运行效果
------------
……
## 八、特别鸣谢Links
------------
- [Yii2](http://www.yiiframework.com/)
- [Yii2 Extension](http://www.yiiframework.com/extension/yii2-workflow-manager)


