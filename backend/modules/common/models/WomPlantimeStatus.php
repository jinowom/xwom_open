<?php

namespace backend\modules\common\models;

use Yii;

/**
 * This is the model class for table "wom_plantime_status".
 *
 * @property int $id
 * @property string $name
 * @property int $position
 *
 * @property WomPlan[] $womPlans
 */
class WomPlantimeStatus extends \yii\db\ActiveRecord
{
    use \common\traits\MapTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wom_plantime_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name', 'position'], 'required'],
            [['id', 'position'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'position' => Yii::t('app', 'Position'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWomPlans()
    {
        return $this->hasMany(WomPlan::className(), ['time_status' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return WomPlantimeStatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WomPlantimeStatusQuery(get_called_class());
    }
}
