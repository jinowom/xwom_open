<?php
/**
 * This is the model class for table "ConfigWatermark";
 * @package backend\modules\common\models;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2021-01-07 16:57 */
namespace backend\modules\common\models;

use Yii;

/**
 * This is the model class for table "config_watermark".
 *
 * @property int $id
 * @property string $name 变量名
 * @property string $name_en 英文名称
 * @property string $watermark_url 水印图片路径
 * @property string $watermark_text 文字水印内容
 * @property int $x 坐标X
 * @property int $y 坐标y
 * @property string $description 操作说明
 * @property int $type 变量类型 1：图片水印；2：文字水印：
 * @property int $status 状态 1: 开启; 0:关闭
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 * @property int $created_id 添加者
 * @property int $updated_id 修改者
 * @property int $is_del 是否删除 0否 1是
 */
class ConfigWatermark extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait; 
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config_watermark';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'status', 'text_size', 'created_at', 'updated_at', 'created_id', 'updated_id', 'is_del'], 'integer'],
            [['x', 'y'], 'string', 'max'=>11],
            [['name', 'name_en'], 'required'],
            [['name', 'name_en', 'watermark_url', 'watermark_text', 'description'], 'string', 'max' => 255],
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
            'watermark_url' => 'Watermark Url',
            'watermark_text' => 'Watermark Text',
            'text_size' => 'Text Size',
            'x' => 'X',
            'y' => 'Y',
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
    public static function getList($parames){
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
        $model = new ConfigWatermark();
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
        $model = self::findOne($request['id']);
        $model->attributes = $request;
        if ($model->save()) {
            return true;
        } else {
            return json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE);
        }
    }
}
