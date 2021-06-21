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
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use app\core\db\Query;
use app\core\db\ActiveDataProvider;
use app\core\filters\ActiveDataFilter;
use app\core\helpers\RequestHelper;
use app\core\validate\ParamsValidate;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{

    /**
     * 列表
     * 默认分页参数
     *      每页条数：per-page
     *      第几页： page
     * @return array
     */
    public function actionIndex(){
        $reqData = RequestHelper::get();
        $query = new Query();
        $query->select(['t.*']);
        $query->from(<?= $modelClass ?>::tableName() . ' t');

        $dataFilter = new ActiveDataFilter([
            'searchModel' => <?= $modelClass ?>::className()
        ]);

        $dataFilter->load($reqData);

        $filterCondition = $dataFilter->build(false);
        $query->andFilterWhere($filterCondition);
        $query->orderBy('t.id desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $list = $dataProvider->getModels();
        $total = $dataProvider->getTotalCount();

        $result = [
            'list' => $list,
            'total' => $total,
        ];

        return $this->success($result);
    }

    /**
     * 创建
     * @return array
     */
    public function actionCreate()
    {
        $reqData = RequestHelper::post();
        //验证
        $error = ParamsValidate::validateError($reqData, [
            ['name', 'required', 'message' => '请填写名称']
        ]);
        if($error){
            return $error;
        }
        $model = new <?= $modelClass ?>();
        $model->load($reqData);
        $model->create_time = date('Y-m-d H:i:s');
        $model->update_time = date('Y-m-d H:i:s');

        $model->save();
        if($model->hasErrors()){
            return $this->error($model->getFirstErrorMsg());
        }
        return $this->success($model->toArray());
    }


    /**
     * 修改
     * @return array
     */
    public function actionUpdate()
    {
        $reqData = RequestHelper::post();
        //验证
        $error = ParamsValidate::validateError($reqData, [
            ['id', 'required', 'message' => '参数有误'],
        ]);
        if($error){
            return $error;
        }
        $model = <?= $modelClass ?>::findOne($reqData['id']);
        if(!$model){
            return $this->error('信息不存在');
        }
        $model->load($reqData);
        $model->update_time = date('Y-m-d H:i:s');

        $model->save();
        if($model->hasErrors()){
            return $this->error($model->getFirstErrorMsg());
        }
        return $this->success($model);
    }

    /**
     * 详情
     * @param $id
     * @return array
     */
    public function actionView($id)
    {
        $model = <?= $modelClass ?>::findOne($id);
        if(!$model){
            return $this->error('信息不存在');
        }
        return $this->success($model);
    }

    /**
     * 删除
     */
    public function actionDelete()
    {
        $id = RequestHelper::post('id');
        if(!$id){
            return $this->error('参数有误');
        }
        //模型
        $model = <?= $modelClass ?>::findOne($id);
        if(!$model){
            return $this->success();
        }
        $model->status = -1;
        $model->update_time = date('Y-m-d H:i:s');
        $model->save();
        if($model->hasErrors()){
            return $this->error($model->getFirstErrorMsg());
        }
        return $this->success();
    }
}