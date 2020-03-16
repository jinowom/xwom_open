<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin_team_status}}".
 *
 * @property int $id
 * @property string $name
 * @property int $position
 *
 * @property AdminTeam[] $adminTeams
 */
class AdminTeamStatus extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_team_status}}';
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
    public function getAdminTeams()
    {
        return $this->hasMany(AdminTeam::className(), ['t_status' => 'id']);
    }
}
