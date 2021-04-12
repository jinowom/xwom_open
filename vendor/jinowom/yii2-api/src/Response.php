<?php
namespace jinowom\api;
use Yii;
use yii\base\Component;
use yii\helpers\Json;
 
class Response extends Component {  
    /**
     * 输出json
     *
     * @param string $msg
     * @param integer $code
     * @param array $data
     * @return void
     */    
    public function render($msg='',$code=200,$data=''){
        $resarr=[
            'code'=>$code,
            'msg'=>$msg,
            'data'=>$data,
        ];
        header('Content-Type:application/json;charset=utf-8');
        return Json::encode($resarr);
    }

 
    /**
     * 错误输出
     *
     * @param [type] $e
     * @return void
     */
    public function renderException($e,$code=500){    
        return $this->render($e->getMessage(),$e->getCode()?:$code);
    }
    /**
     * 成功的输出
     *
     * @param mix $data
     * @param integer $code 默认是200
     * @return void
     */
    function renderSuccess($data,$code=200,$msg='success'){
        return $this->render($msg,$code,$data);
    }
}
