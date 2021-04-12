<?php

namespace backend\modules\common\models;

use Yii;

/**
 * This is the model class for table "config_page_manage".
 *
 * @property int $id 分页id
 * @property int $app_id 关联应用ID
 * @property int $num 每页显示条数
 * @property string $page_name 分页变量名称
 * @property string $controller 分页所属控制器的名称
 * @property string $action 分页所属方法的名称
 * @property string $show_name 显示的名称
 * @property int $type 1：前台，2：后台
 * @property int $status 0：不使用分页，1：使用分页
 * @property int $siteid 站点id
 * @property int $updated_id 修改者
 * @property int $created_id 添加者
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 * @property int $is_del 是否删除 0否 1是
 */
class ConfigPageManage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config_page_manage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['app_id', 'num', 'type', 'status', 'siteid', 'updated_id', 'created_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['page_name', 'controller', 'action', 'show_name'], 'required'],
            [['page_name', 'controller', 'action', 'show_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'num' => 'Num',
            'page_name' => 'Page Name',
            'controller' => 'Controller',
            'action' => 'Action',
            'show_name' => 'Show Name',
            'type' => 'Type',
            'status' => 'Status',
            'siteid' => 'Siteid',
            'updated_id' => 'Updated ID',
            'created_id' => 'Created ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_del' => 'Is Del',
        ];
    }
    /**
     * beforeSave 存储数据库之前事件的实务的编排、注入；
     */ 
    public function beforeSave($insert) {
    	if(parent::beforeSave($insert)) {
                $admin_id = Yii::$app->user->getId();
    		if($insert){
                    $this->created_at = time();
                    $this->updated_at = time();
                    if($admin_id){
                       $this->created_id = $admin_id; 
                       $this->updated_id = $admin_id; 
                    }	
    		}
    		else {
                    $this->updated_at = time();
                    $this->updated_id= $admin_id;
    		}
    		return true;			
    	}
    	else {
    		return false;
    	}
    }

    //获取分页配置列表
    public static function getPageList($parames){
        return self::find()->from(ConfigPageManage::tableName() . ' page')
                    ->select('page.*, soft.title')
                    ->LeftJoin('reg_software as soft','soft.id = page.app_id')
                    ->andWhere(['page.is_del'=>0])
                    ->andFilterWhere(['or',['like','soft.title',$parames],['like','page.controller',$parames]]);
    }

    //分页配置提交表单
    public static function createDo($request){
        $exit = ConfigPageManage::find()->andWhere(['is_del'=>0])
                ->andWhere(['controller'=>$request['controller']])
                ->andWhere(['action'=>$request['action']])
                ->asArray()->one();
        //检测这个控制器+方法是否唯一
        if(!empty($exit)){
            return '该控制器方法分页以配置请重新填写';
        }
        // 添加信息入库
        $model = new ConfigPageManage();
        $model->attributes = $request;
        if ($model->save()) {
            return true;
        } else {
            return json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE);
        }
    }
    //提交修改表单
    public static function updateDo($request){
        // 添加信息入库
        $model = self::findOne($request['id']);
        $model->attributes = $request;
        if ($model->save()) {
            return true;
        } else {
            return json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE);
        }
    }
}
