
### 这是一个基于yii2的restful接口组织方案，自动生成在线文档供前端工程师阅读

这是一个非严格的restful风格的文档，输出格式仅支持json,请求动作只支持GET和POST.

### 特性
- module的方式发布，无侵入
- 接口显式声明
- 接口版本管理
- 基于yii2 自身的 validator 
- 自动生成接口文档
- 在线测试工具
- 关键字搜索相关接口
- 自由灵活的配置，关键类可以自定义替换




### 安装

```
php composer.phar require --prefer-dist jinowom/yii2-api
```


仓库地址 : https://github.com/jinowom/yii2-api

packagist.org : 

### 配置

```
'modules'=>[
    'myapi'=>[
        'class'=>'jinowom\api\Module',
        'apiConfig'=>require(__DIR__ . '/apiConfig.php'),    		
    ]
],
'bootstrap' => ['myapi'], 
'components	=>[
    'user' => [
        'identityClass' => 'jinowom\demo\Identity',  //这是用与测试的Identity
    ],
]
```

myapi是module的名字,请自定义

接口文档 请访问  

demo 访问 ：稍后补充上来

文件上传api demo: 稍后补充上来

文件上传demo: 稍后补充上来


配置选项：

- apiConfig : 接口定义的配置
- defaultVersion : 默认的版本号
- overviewHtml： 文档页面overview的模板
- docTitle：     文档中心的标题
- responseClass： 响应的处理类， 如果想实现输出xml格式，请继承jinowom\api\Response重写render方法,配置即可
- errorHandlerClass： 异常处理类，如果想实现默认的错误code不是500，而是 0，请重写该类，配置即可
- openAccess:  是否开放访问文档中心，默认true表示开放。如果必须后台登陆之后才能访问请设置为false,继承jinowom\api\Module 重写checkAccess()方法，demo请看 jinowom\demo\Module
- authType：    Token认证处理类型配置,多个使用逗号分隔,eg:'query,bearer,header'. 现支持下面几种认证方式:
    - query: 请求参数中认证，即把token放在地址中 http://server.example.com/apiurl?token=token
    - header:  http请求头 X-Api-Key:token , 下面是http协议的请求示例：
    ```
    GET /apiurl HTTP/1.1
    Host: server.example.com
    X-Api-Key: token
    ```
    - bearer：http请求头 Authorization ,下面是http协议的请求示例：
    ```
    GET /apiurl HTTP/1.1
    Host: server.example.com
    Authorization: Bearer token
    ```
- builtInAuthTypes：认证类型的集合，可使用的认证过滤器参阅 \yii\filters\auth

### api配置
```
// apiConfig.php  ,可以参阅demo文件  jinowom\demo\apiConfig
return [    
    'v1'=>[		    
        'user'      => '用户',  //key 不含有.符号的，用来分类
        'user.get'  => ['class'=>'jinowom\demo\v1\user\Get','auth'=>true], 
        'user.get2' => 'jinowom\demo\v1\user\Get', //等同['class'=>'jinowom\demo\v1\user\Get']
        'user.get3' => \jinowom\demo\v1\user\Get::class,  
    ],
    'v2'=>[
        'user'      =>'用户',
        'user.get' => \jinowom\demo\v2\user\Get::class,  
        //.....
    ]
];
```
每个接口都有如下选项：

- class: 类的路径
- auth: 是否需要登陆认证,默认 false
- apiDescription: 接口的描述
- verbs: 支持的请求的动作，默认是 GET,POST
- [自定义的属性]，每个接口中的 public属性也可以在这里配置

### 实现自己的接口
所有接口类必须要实现接口  \jinowom\api\IApi

```
class Test extends \jinowom\api\IApi{ 
    function params(){}
    function handle($params){}
    function returnJson(){}
}
```
其中：

- params()  必须实现，定义输入的参数，和基本的校验规则
- handle()  必须实现，逻辑处理。对于需要认证的接口中可以使用 Yii::$app->user->identity 获取用户的实例
- returnJson()  返回示例，用于生成接口文档中的示例 json
- handle 的注解用来生成在线文档的返回字段/备注说明,规则为:
    -  @return [类型] [字段] [说明]  , 定义输出"返回字段"
    -  @mark xxxx  ,定义输出"备注说明"

params() 示例如下：

```
function params(){
    return [
        'myfield'=>['type'=>'string','validate'=>'required,number,in:1|2|3','demo'=>'123','description'=>'描述'],
        'file1'=>['type'=>'string','validate'=>[
            'file'=>[
                'extensions'=>'jpg,gif,png',
                'minSize'=>10240,
                'maxFiles'=>1,
            ],
            'required'
        ],'demo'=>'123','description'=>'二级制流文件上传，name=file1'],
    ];
}
```

其中：

- key 为字段名
- type： 类型，可以使用 string,boolean,int,float 对于输入不做任何强制校验,校验类型请使用validate
- validate: 验证规则，参阅 yii\validators下的验证器,多个验证器请使用逗号(,)分隔，现在支持:
    - required: 必填
    - trim: 清空输出参数的前后空格
    - number: 数字
    - boolean: 布尔验证
    - date: 日期格式
    - email: 邮箱地址
    - url: url地址
    - ip: ip地址    
    - in: 范围内验证。 eg：in:1|2|3 表示输入的值必须是 1,2,3 其中的一个值
    - _xxxx: 带有下划线开头表示自定义验证
    - 多参数的验证 validate 应该是一个数组，key为验证器的名称，value为验证的属性,参见 yii\validators\Validator::builtInValidators ： 
       - `boolean`: [[BooleanValidator]]
       - `captcha`: [[\yii\captcha\CaptchaValidator]]
       - `compare`: [[CompareValidator]]
       - `date`: [[DateValidator]]
       - `datetime`: [[DateValidator]]
       - `time`: [[DateValidator]]
       - `default`: [[DefaultValueValidator]]
       - `double`: [[NumberValidator]]
       - `each`: [[EachValidator]]
       - `email`: [[EmailValidator]]
       - `exist`: [[ExistValidator]]
       - `file`: [[FileValidator]]
       - `filter`: [[FilterValidator]]
       - `image`: [[ImageValidator]]
       - `in`: [[RangeValidator]]
       - `integer`: [[NumberValidator]]
       - `match`: [[RegularExpressionValidator]]
       - `required`: [[RequiredValidator]]
       - `safe`: [[SafeValidator]]
       - `string`: [[StringValidator]]
       - `trim`: [[FilterValidator]]
       - `unique`: [[UniqueValidator]]
       - `url`: [[UrlValidator]]
       - `ip`: [[IpValidator]]


### 组织文件目录demo

<p>稍后补充上来</p>

### 文档中心预览

<p>稍后补充上来</p>