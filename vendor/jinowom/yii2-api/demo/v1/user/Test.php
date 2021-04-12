<?php 
namespace jinowom\demo\v1\user;
 
use jinowom\api\exception\LogicException;
 

class Test extends \jinowom\api\IApi{  
    public $apiDescription = "注释和验证的完全测试";    
 
    public $auth=true;

    function params(){
        return [ 
            'test1'=>['type'=>'string','validate'=>'required','demo'=>'test,xtest','description'=>'必填'],
            'test2'=>['type'=>'string','validate'=>'required,url','demo'=>'http://a.com','description'=>'url地址'],
            'test3'=>['type'=>'string','validate'=>'trim,required,ip','demo'=>'1.1.1.1','description'=>'ip'],
            'test4'=>['type'=>'int','validate'=>'required,trim,number','demo'=>'123','description'=>'数字'],
            'test5'=>['type'=>'string','validate'=>'required,in:a|b|c','demo'=>'a','description'=>'in验证,测试请传入c'],
            'test6'=>['type'=>'string','validate'=>'_checktest6','demo'=>'php','description'=>'自定义验证，测试请传入 yii2'],
            'test7'=>['type'=>'boolean','validate'=>'boolean','demo'=>'1','description'=>'boolean验证'],
            'test8'=>['type'=>'float','validate'=>'','demo'=>'','description'=>'空验证'],
            'test9'=>['type'=>'file','validate'=>[
                'file'=>[
                    'extensions'=>'png,jpg,gif',
                    'minSize'=>5200,
                    'uploadRequired'=>'请上传一个图片',
                    'maxFiles'=>1,      
                ],
                'safe',
                'in'=>[
                    'range'=>[1,2]
                ],
            ],'demo'=>'','description'=>'文件验证'],
        ];
    }
 
    /**
     * @return array params[] 传入的字段返回
     * @return object obj 注释的对象测试
     * @return string obj.v1 v1
     * @return object obj.v2 obj.注释的对象测试
     * @return string obj.v2.name 姓名
     * @return string obj.v2.sex 性别
     * @return array obj.v2.loginLogs[] 登陆的日志
     * @return ip obj.v2.loginLogs[].ip 登陆的日志ip
     * @return date obj.v2.loginLogs[].date 登陆的日志日期
     * @return string obj.v3 v3
     */
    function handle($params){ 

        //返回个特殊的code 需要前端特殊处理
        if($params['test1']!='xtest'){
            throw new LogicException('test1 不正确，请输入 xtest',501);
        }  

        //返回固定的code=500，前端统一处理
        if($params['test5']!='c'){
            throw new LogicException('test5 不正确');
        }

        return [
            'params'=>$params,
            'obj'=>[
                'v1'=>'1',
                'v2'=>[
                    'name'=>'frank',
                    'sex'=>'男',
                    'loginLogs'=>[
                        ['ip'=>'127.0.0.1','date'=>'2019-2-21'],
                        ['ip'=>'127.0.0.2','date'=>'2019-2-22'],
                    ]
                ],
                'v3'=>999,
            ]
        ];

    } 
 
    function _checktest6($a,$b){
       
        if($this->test6!='yii2'){
            $this->addError('test6','test6 输入不正确');
        }
    }

    function returnJson(){
        return '
        {"code":200,"msg":"success","data":{"params":{"test1":"test","test2":"http://a.com","test3":"1.1.1.1","test4":"123","test5":"c","test6":"yii2","test7":"1","test8":"123"},"obj":{"v1":"1","v2":{"name":"frank","sex":"男","loginLogs":[{"ip":"127.0.0.1","date":"2019-2-21"},{"ip":"127.0.0.2","date":"2019-2-22"}]},"v3":999}}}';
    }
 
}
 