<?php

namespace common\models;

use Yii;

class ArticleCat extends \yii\db\ActiveRecord
{
    /**
     * 内容模型
     * @var array
     */
    public $modelArray = [
        'article' => '文章',
        'download' => '下载'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_cat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_name', 'model'], 'required'],
            [['sort_order', 'parent_id'], 'integer'],
            [['cat_name', 'keywords', 'cat_desc'], 'string', 'max' => 255],

            [['sort_order'], 'default', 'value' => function(){
                return 50;
            }],
            [['parent_id'], 'default', 'value' => function(){
                return 0;
            }],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat_id' => '分类ID',
            'cat_name' => '文章分类名称',
            'keywords' => '关键字',
            'cat_desc' => '描述',
            'sort_order' => '排序',
            'show_in_nav' => '是否显示在导航栏',
            'parent_id' => '上级分类',
            'model' => '模型'
        ];
    }

    /**
     * 新增、编辑分类
     * @return bool
     */
    public function addData(){
        if ($this->validate()) {
            $this->model = serialize($this->model);
            return $this->save(false);
        }
        return false;
    }

    /**
     * 获取分类 ( select下拉数组 )
     * @param bool $is_det 是否需要默认分类
     * @return mixed
     */
    public function parent($is_det = true)
    {
        $list = $this->find()->orderBy('sort_order')->asArray()->all();

        $list = $this->parentSort($list);

        foreach ($list as $key => $val) {
            $list[$key]['cat_name'] = $val['html'] . $val['cat_name'];

        }
        // 需要默认分类
        if ($is_det) {
            return array_merge([['cat_id' => 0, 'cat_name' => '顶级分类']], $list);
        }
        return $list;
    }


    // 排序分类
    Public function parentSort($cate, $html = '&nbsp;&nbsp;&nbsp;&nbsp;', $pid = 0, $level = 0){
        $arr = array();
        foreach($cate as $v){
            if ($v['parent_id'] == $pid){
                $v['level'] = $level +1;
                $v['html'] = str_repeat($html, $level);
                $arr[] = $v;
                $arr = array_merge($arr, $this->parentSort($cate, $html, $v['cat_id'], $level + 1));

            }
        }
        return $arr;
    }

    /**
     * 所有分类 （ 'key' => 'value' ）的形式
     * @return mixed
     */
    public function parentNews()
    {
        $list = $this->find()->orderBy('sort_order')->asArray()->all();

        $list = $this->parentSort($list);

        // 组合成 （名 =》值） 的形式
        $listNews = ['0' => '全部分类'];

        foreach ($list as $val) {
            $listNews[$val['cat_id']] = $val['html'] . $val['cat_name'];
        }
        return $listNews;
    }

    /**
     * 分类绑定的模型 [html radio 格式的]
     * @param $cat_id
     * @return array
     */
    public function cat_model($cat_id)
    {
        $modelList = self::findOne($cat_id)->model;
        $modelList = unserialize($modelList);
        $list = [];
        foreach ($modelList as $val) {
            $list[$val] = $this->modelArray[$val];
        }
        return $list;
    }

}
