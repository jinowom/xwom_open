<?php

namespace console\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "t_task_log".
 *
 * @property int $id
 * @property string $created_at 创建时间
 * @property string $created_by 创建人
 * @property string $updated_at 修改时间
 * @property string $updated_by 修改人
 * @property string $task_id 任务ID
 * @property string $start_time 开始执行时间
 * @property string $finish_time 结束执行时间
 * @property string $info 日志信息
 */
class TaskLogModel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_task_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id'], 'required'],
            [['created_at', 'updated_at', 'task_id', 'start_time', 'finish_time'], 'string', 'max' => 50],
            [['created_by', 'updated_by'], 'string', 'max' => 100],
            [['info'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'task_id' => 'Task ID',
            'start_time' => 'Start Time',
            'finish_time' => 'Finish Time',
            'info' => 'Info',
        ];
    }
}