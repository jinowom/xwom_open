<?php

namespace common\models\config;

use Yii;

/**
 * This is the model class for table "config_variable".
 *
 * @property int $id ID
 * @property string $name 变量名
 * @property string $name_en 英文名称
 * @property string $description 操作说明
 * @property int $type 0:全局； 1：前台；2：后台；3：API；
 * @property int $status 状态 1: 开启; 0:关闭
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 * @property int $created_id 添加者
 * @property int $updated_id 修改者
 * @property int $is_del 是否删除 0否 1是
 */
class ConfigVariable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config_variable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'name_en','value'], 'required'],
            [['id', 'type', 'status', 'created_at', 'updated_at', 'created_id', 'updated_id', 'is_del'], 'integer'],
            [['name', 'name_en', 'description','value'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'name_en' => 'Name En',
            'value' => 'value',
            'description' => 'Description',
            'type' => 'Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_id' => 'Created ID',
            'updated_id' => 'Updated ID',
            'is_del' => 'Is Del',
        ];
    }
    /**
     * beforeSave 存储数据库之前事件的实务的编排、注入；
     */ 
    public function beforeSave($insert)
    {
    	if(parent::beforeSave($insert))
    	{
                $admin_id = Yii::$app->user->getId();
    		if($insert)
    		{
                    $this->created_at = time();
                    $this->updated_at = time();
                    if($admin_id){
                       $this->created_id = $admin_id; 
                       $this->updated_id = $admin_id; 
                    }	
    		}
    		else 
    		{
                    $this->updated_at = time();
                    $this->updated_id= $admin_id;
    		}
    		return true;			
    	}
    	else 
    	{
    		return false;
    	}
    }

    //获取全局变量列表
    public static function getVariableList($parames){
        return self::find()->andWhere(['is_del'=>0])
                           ->andFilterWhere(['or',['like','name',$parames],['like','name_en',$parames]]);
    }
    // 提交添加表单
    public static function createDo($request){
        $exit = self::find()->andWhere(['is_del'=>0])
                ->andWhere(['name_en'=>$request['name_en']])
                ->asArray()->one();
        //检测这个控制器+方法是否唯一
        if(!empty($exit)){
            return '该变量已存在请重新填写';
        }
        // 添加信息入库
        $model = new ConfigVariable();
        $model->attributes = $request;
        if ($model->save()) {
            return true;
        } else {
            return json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE);
        }
    }

    //提交修改表单
    public static function updateDo($request){
        $exit = self::find()
                ->andWhere(['is_del'=>0])
                ->andWhere(['name_en'=>$request['name_en']])
                ->andWhere(['!=','id',$request['id']])
                ->asArray()->one();
        //检测这个控制器+方法是否唯一
        if(!empty($exit)){
        return '该变量已存在请重新填写';
        }
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
