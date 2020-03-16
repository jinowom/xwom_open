<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admin_dep_status".
 *
 * @property int $id
 * @property string $name
 * @property int $position
 *
 * @property AdminDep[] $adminDeps
 */
class AdminDepStatus extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_dep_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'position'], 'required'],
            [['position'], 'integer'],
            [['name'], 'string', 'max' => 128],
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
            'position' => 'Position',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminDeps()
    {
        return $this->hasMany(AdminDep::className(), ['d_status' => 'id']);
    }
}
