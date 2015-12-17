<?php

namespace common\models;

use Yii;

class CommentArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment_article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aid', 'content'], 'required'],
            [['aid', 'uid', 'add_time', 'status'], 'integer'],
            [['content'], 'string', 'max' => 255],
            [['add_time'], 'default', 'value' => function(){
                return time();
            }],
            [['status'], 'default', 'value' => function(){
                return 1;
            }],
            [['content'], 'filter', 'filter' => function($value){
                return trim($value);
            }]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'aid' => '文章id',
            'uid' => '用户id',
            'content' => '内容',
            'add_time' => '评论时间',
            'status' => '状态',
        ];
    }

    /**
     * 修改状态
     * @param int $id 评论id
     * @param int $status 状态码 [0, 1]
     * @return bool|int
     */
    public function setStatus($id, $status = 1){
        if (!in_array($status, [0, 1])) {
            return false;
        }
        return CommentArticle::updateAll(['status' => $status], 'id = :id', [':id' => $id]);
    }

    /**
     * 获得文章的评论
     * @param int $aid 文章id
     * @param int $p 当前页
     * @return array
     */
    public function getArticleList($aid, $p = 1)
    {
        $pageSize = 20;
        $offset = ($p - 1) * $pageSize;
        return $this->find()->where(['aid' => $aid, 'status' => 1])->orderBy('id DESC')->asArray()->offset($offset)->limit($pageSize)->all();
    }

    /**
     * 用户的评论列表
     * @param $uid
     * @param int $p
     * @return array
     */
    public function getMemberList($uid, $p = 1)
    {
        $pageSize = 20;
        $offset = ($p - 1) * $pageSize;
        return $this->find()->where(['uid' => $uid, 'status' => 1])->orderBy('id DESC')->asArray()->offset($offset)->limit($pageSize)->all();
    }

}
