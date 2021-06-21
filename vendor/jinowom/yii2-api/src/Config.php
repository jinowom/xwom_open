<?php
namespace jinowom\api;
use yii;
use yii\base\Component;
/**
 * 处理 apis 配置的类
 */
class Config extends Component{ 
    public $apis=[];

    public static function getActiveGroupCfg(){
        $v=Yii::$app->request->get('v','v1');
       
        return Yii::$app->controller->module->apiConfig[$v]; 
    }
    /**
     * 获取全部的goups
     *
     * @return void
     */
    public static function getAllList(){
        $list=Config::getActiveGroupCfg();
        foreach($list as $method=>$params){
            list($group)=explode('.',$method);
            $groups[$group]=$list[$group]?:$group;
        }    
        return $groups;
    }
    /**
     * 获取活动版本的group 信息
     *
     * @return void
     */
    public static function getActiveGroupList(){
        $g=Yii::$app->request->get('g');
        $list=Config::getActiveGroupCfg();
       
        $gname=$list[$g]?:$g;
        unset($list[$g]);
        foreach($list as $method=>$params){
            list($group)=explode('.',$method);
            if($group!=$g){
                unset($list[$method]);
            }           
        }
        $nlist=[];
        foreach($list as $method=>$params){
            if(is_string($params)){
                $nlist[$method]=[
                    'class'=>$params
                ];
            }else{
                $nlist[$method]=$params;
            }
           
            try{         
                $class=Yii::createObject($nlist[$method]);   
                $nlist[$method]['instance']=$class;           
            }catch(\Exception $e){
                $nlist[$method]['apiDescription']=$e->getMessage();
                $nlist[$method]['verbs']='null';
                continue;
            }         
            $nlist[$method]['apiDescription']=$class->apiDescription;
            $nlist[$method]['verbs']=strtoupper($class->verbs);
        }
      
        return [
            'list'=>$nlist,
            'gname'=>$gname
        ];
    }

    /**
     * 获取方法的详情
     *
     * @return void
     */
    public static function getMethodInfo(){
        $method=Yii::$app->request->get('method');
        $v=Yii::$app->request->get('v','v1');
        $glist=Config::getActiveGroupCfg();
        list($group)=explode('.',$method);
        $gname=$glist[$group]?:$group;      

        $methodConfig=$glist[$method];
        try{
            $class=Yii::createObject($methodConfig);
        }catch(\Exception $e){
            echo $e->getMessage();
            die;
        }   
       
        return [
            'gname'=>$gname,
            'class'=>$class
        ];
    }
 
    /**
     * 获取全部的版本集合
     *
     * @return array
     */
    public static function getAllVersions(){
        $all=Yii::$app->controller->module->apiConfig;
        return array_keys($all);        
    }

    /**
     * 获取全部的groupname 集合
     *
     * @return void
     */
    static function searchKey($key){
        $all=Yii::$app->controller->module->apiConfig;
        $list=[];
        $groupkeys=[];
        foreach($all as $v=>$methods){
            foreach($methods as $method=>$params){
                if(strpos($method,'.')===false){
                    $groupkeys[$method]=$params;
                    continue;
                }
                try{
                    $clazz=Yii::createObject($params);
                }catch(\Excepiton $e){
                    continue;
                }
                list($g)=explode('.',$method);
                $keys=[
                    $method,$clazz->apiDescription,$g,get_class($clazz),$v,$groupkeys[$g]
                ];
                $keys=join(',',$keys);
                if(stripos($keys,$key)!==false){
                    $groups[$v][$method]['apiDescription']=$clazz->apiDescription;
                    $groups[$v][$method]['keys']=$keys;
                    $list[]=[
                        'method'=>$method,
                        'v'=>$v,
                        'apiDescription'=>$clazz->apiDescription,
                        'verbs'=>$clazz->verbs,
                        'auth'=>$clazz->auth,
                        'g'=>$g

                    ];
                }
               
            }
        }
        
        return ($list);
    } 
}
