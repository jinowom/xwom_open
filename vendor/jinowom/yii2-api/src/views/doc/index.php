 
<h1 class="page-header">接口文档概述</h1>


<ul class=ul > 
  <li>本文供前端开发工程师使用</li>
  <li>接口请求地址统一格式为: <?=\Yii::$app->request->hostInfo ?><?=\yii\helpers\Url::to(['index/index'])?>?v=v1&method=user.get 其中:
    <ul>
      <li><code>v</code>: 版本号，非必填，如：v1 ， 默认为<?=Yii::$app->controller->module->defaultVersion ?></li>
      <li><code>method</code>: 调用的接口名称，如:user.get</li>
    </ul>
  </li>
  <li>支持的请求动作有 <code>GET</code>/<code>POST</code>/<code>GET,POST</code> 等三种情况</li>
  <li>返回格式仅支持json格式,下面是几个完整的json示例: <br>
    <ul>
        <li><code>{"code":200,"msg":"success","data":null}</code></li>
        <li><code>{"code":500,"msg":"请传入xx字段","data":""}</code></li>
        <li><code>{"code":501,"msg":"登陆错误","data":""}</code></li>
        <li><code>{"code":200,"msg":"success","data":[]}</code></li>
        <li><code>{"code":200,"msg":"success","data":[{a:1,b:2},{a:3,b:4}]}</code></li>
    </ul>

    其中返回的字段：
    <ul>
      <li>code：整数，状态码。其中200表示成功（http协议状态码为200）；其他非200，如500，表示出现错误（http协议状态码为500）</li>
      <li>msg：字符串，返回的提示消息</li>
      <li>data：返回的具体数据</li>
    </ul>
 
  </li>

    <li>输入参数校验的类型 <a name=validate ></a>
        <ul>
            <li>required: 必填</li>
            <li>trim: 清空输出参数的前后空格</li>
            <li>number: 数字</li>
            <li>boolean: 布尔验证, 请传入 1或者0</li>
            <li>date: 日期格式</li>
            <li>email: 邮箱地址</li>
            <li>url: url地址</li>
            <li>ip: ip地址</li>
            <li>in: 范围内验证。 eg：in:1|2|3  表示输入的值必须是 1,2,3 其中的一个值 </li>
            <li>_xxxx: 带有下划线开头表示自定义验证</li>
        </ul>
    </li>
</ul>

 


<style>
.ul li{line-height:25px}
</style>