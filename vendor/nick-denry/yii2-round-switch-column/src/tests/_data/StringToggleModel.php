<?php

namespace nickdenry\grid\toggle\tests\_data;

use yii\db\ActiveRecord;


/**
 * This is the model class for table "pages".
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string $alias
 * @property string $created_at
 * @property string $updated_at
 */
class StringToggleModel extends \yii\db\ActiveRecord
{
    public $switchValues = [
        'is_published' => [
            'on' => 'yes',
            'off' => 'no'
        ]
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            ['is_published', 'string'],
        ];
    }
}
