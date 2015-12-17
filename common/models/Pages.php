<?php

namespace common\models;

use Yii;

class Pages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'title', 'content'], 'required'],
            [['add_time'], 'integer'],
            [['content'], 'string'],
            [['name', 'title'], 'string', 'max' => 50],
            [['add_time'], 'default', 'value' => function(){
                return time();
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '分类名称',
            'title' => '标题',
            'content' => '内容',
            'add_time' => '创建时间',
        ];
    }
}
