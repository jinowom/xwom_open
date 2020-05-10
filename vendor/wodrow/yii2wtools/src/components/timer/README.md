# Yii,timer,tool
#### https://github.com/ciniran/yii-timer
-  本工具主要用于调试PHP代码的执行时间。
对于自己写出来的代码，感觉执行效率有问题的时候可以用本工具来进行一个辅助分析。
- 本工具是单例运行，以YII的组件的形式开发，在YII应用实例化完成之后就可以调用运行
- 本工具默认调用YII的log记录输出时间，也可以配置为文件存储
### 配置方法
- 配置方法如下,(配置到Yii组件 common/config/main-local.php文件中)：

````
'components'=>[
   'timer'  => [
        'class' => \common\members\wodrow\components\timer\Timer::class,
        'saveLog' => true,         //开启文件保存  
        'logFileName'=>'log.txt'   //保存的文件名(位置:runtime文件夹下,）
                                   //如果未设置则保存到YII日志记录中
        ],
    ],
````
- 使用方法如下：
````

    \Yii::$app->timer->start(); //计时开始
    doSomething…… //程序代码块
    
    \Yii::$app->timer->point(); //中间时间切分点
    doSomething…… //程序代码块
    
    \Yii::$app->timer->point(); //中间时间切分点
    doSomething…… //程序代码块
    
    \Yii::$app->timer->end(); //计时结束

````
- 你也可以直接通过代码输出
````
      Yii::$app->timer->start();
      sleep(1);
      Yii::$app->timer->point();
      sleep(1);
      Yii::$app->timer->point();
      sleep(1);
      Yii::$app->timer->point();
      sleep(1);
      Yii::$app->timer->end();
      var_dump(Yii::$app->timer->getRecords());
      die();
````


- 日志最终输出结果如下：
````
array (size=4)
  0 => 
    array (size=8)
      '开始时间' => float 1534210404.9903
      '结束时间' => float 1534210405.9917
      '当前用时' => float 1001.335
      '总用时' => float 1001.335
      '开始文件' => string 'C:\workspace\base\frontend\controllers\SiteController.php' (length=57)
      '开始行' => int 75
      '结束文件' => string 'C:\workspace\base\frontend\controllers\SiteController.php' (length=57)
      '结束行' => int 77
  1 => 
    array (size=8)
      '开始时间' => float 1534210404.9903
      '结束时间' => float 1534210406.9925
      '当前用时' => float 1000.781
      '总用时' => float 2002.116
      '开始文件' => string 'C:\workspace\base\frontend\controllers\SiteController.php' (length=57)
      '开始行' => int 77
      '结束文件' => string 'C:\workspace\base\frontend\controllers\SiteController.php' (length=57)
      '结束行' => int 79
  2 => 
    array (size=8)
      '开始时间' => float 1534210404.9903
      '结束时间' => float 1534210407.9931
      '当前用时' => float 1000.64
      '总用时' => float 3002.756
      '开始文件' => string 'C:\workspace\base\frontend\controllers\SiteController.php' (length=57)
      '开始行' => int 79
      '结束文件' => string 'C:\workspace\base\frontend\controllers\SiteController.php' (length=57)
      '结束行' => int 81
  3 => 
    array (size=8)
      '开始时间' => float 1534210404.9903
      '结束时间' => float 1534210408.9936
      '当前用时' => float 1000.526
      '总用时' => float 4003.282
      '开始文件' => string 'C:\workspace\base\frontend\controllers\SiteController.php' (length=57)
      '开始行' => int 81
      '结束文件' => string 'C:\workspace\base\frontend\controllers\SiteController.php' (length=57)
      '结束行' => int 83
````
