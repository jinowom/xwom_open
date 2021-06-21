<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\db\Query;
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
//use app\...\helpers\RequestHelper;//根据情况引用RequestHelper助手类，并需要自行封装

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    public function actionIndex(){
        if (Yii::$app->request->isAjax) {
            $this->getList();
        }else{
            return $this->render('index');
        }
    }

    //获取列表
    public function getList(){
        $this->pageSize = Yii::$app->request->get('limit',5);
        $this->page = Yii::$app->request->get('page',1);
        $parames = Yii::$app->request->get('parames',"");
        $query = <?= $modelClass ?>::getList($parames);
        $this->sidx = 'created_at';
        return $this->getJqTableData($query,"");
    }

    //添加
    public function actionCreate(){
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->post();
            $res = <?= $modelClass ?>::createDo($request);
            if($res === true){
                $this->returnSuccess('','success');
            }else{
                $this->returnError('',$res);
            }
        }else{
            return $this->render('create');
        }
    }

    //修改
    public function actionUpdate(){
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->post();
            $res = <?= $modelClass ?>::updateDo($request);
            if($res === true){
                $this->returnSuccess('','success');
            }else{
                $this->returnError('',$res);
            }
        }else{
            $id = Yii::$app->request->get('id',"");
            $model = <?= $modelClass ?>::findOne($id);
            return $this->render('update',['model'=>$model]);
        }
    }

    //删除 --修改is_del状态（0可用 1删除）
    public function actionDelete(){
        $id = Yii::$app->request->post('id',"");
        $exam = <?= $modelClass ?>::find()->where(['is_del'=>0,'id'=>$id,'status'=>1])->one();
        $model = <?= $modelClass ?>::findOne($id);
        $model->is_del = 1;
        if(!empty($exam)){
            $this->returnError('','该变量正在被使用不可删除');
        }else{
            if ($model->save()) {
                $this->returnSuccess('','success');
            } else {
                $this->returnError('',json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
            }
        }
    }
    
    //查看
    public function actionView(){
        $id = Yii::$app->request->get('id',"");
        $model = ConfigWatermark::findOne($id);
        return $this->render('view',['model'=>$model]);
    }

    //批量删除父级目录
    public function actionDeleteAll(){
        $id = Yii::$app->request->get('id',"");
        $array = explode(',',$id);
        unset($array[0]);
        if(!empty($array)){
            foreach ($array as $key => $value) {
                $model = <?= $modelClass ?>::findOne($value);
                $model->is_del = 1;
                $model->save();
            }   
            $this->returnSuccess('','success');
        }else{
            $this->returnError('','请选择要删除的数据');
        }
    }
}