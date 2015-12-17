<?php

namespace backend\models;

use Yii;

class UserLog extends \yii\db\ActiveRecord
{
    public $first_time; // 用于搜索的开始时间
    public $last_time; // 结束时间

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'model_id', 'add_time'], 'integer'],
            [['action_ip', 'model', 'remark'], 'required'],
            [['action_ip'], 'string', 'max' => 15],
            [['model'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '后台用户id',
            'action_ip' => 'IP',
            'model' => '触发行为的表',
            'model_id' => '触发行为表的id',
            'remark' => '备注',
            'add_time' => '操作时间',
        ];
    }

    /**
     * 新增后台管理员操作日志
     * @param string $model 操作的数据表
     * @param int $model_id 被操作数据表的id
     * @param int $remark 操作说明
     * @param string $action 动作 [ 'add' => '新增', 'edit' => '编辑', 'delete' => '删除' ]
     */
    public static function addData($model, $model_id, $remark, $action)
    {
        Yii::$app->db->createCommand()->insert('{{%user_log}}', [
            'uid' => Yii::$app->user->id,
            'action_ip' => Yii::$app->request->getUserIP(),
            'model' => $model,
            'model_id' => $model_id,
            'remark' => $remark,
            'add_time' => time(),
            'action' => $action
        ])->execute();
    }

}
