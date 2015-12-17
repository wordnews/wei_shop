<?php

namespace common\models;

use Yii;

/**
 * 文章内容模型
 * Class ArticleArticle
 * @package common\models
 */
class ArticleArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
        ];
    }

    public function addData($id)
    {
        $this->id = $id;
        return $this->save();
    }

}
