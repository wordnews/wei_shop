<?php

namespace common\models;

use Yii;
use common\helpers\File;

class ArticleDownload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_download}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['file_path'], 'file', 'extensions' => 'jpg, png, gif, jpeg'],
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
            'file_path' => '文件',
        ];
    }

    public function addData($id)
    {
        /* 处理上传的图片 */
        if ($_FILES['ArticleDownload']['error']['file_path'] === 0) {
            /* 把文件的路劲赋值给image字段 */
            $this->file_path = File::uploadImage($this, 'file_path', 'article');
        }

        if (!$this->id) $this->id = $id;
        return $this->save();
    }

}
