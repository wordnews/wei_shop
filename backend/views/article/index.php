<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\ArticleCat;

$this->title = $this->params['meta_title'] . $this->exTitle;
$this->params['breadcrumbs'][] = $this->params['meta_title'];
?>
<div class="article-index">

    <div class="main_title_wrapper clear">
        <h3><?= Html::encode($this->params['meta_title']) ?></h3>
    
        <p class="action">
            <?/*= Html::a('添加新文章', ['create'], ['class' => 'btn btn-success']) */?>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">添加新文章</button>
        </p>

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="<?= Url::to(['article/create']) ?>" method="post">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">请选择分类和模型</h4>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="recipient-name" class="control-label">选择分类:</label>
                                <?= Html::decode(Html::dropDownList('cat_id', Yii::$app->request->get('cat_id'), $catList, ['class' => 'form-control cat_ids'])) ?>
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="control-label">选择模型:</label>
                                <div class="radios">

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                        <?php
                            $request = Yii::$app->getRequest();
                            echo Html::hiddenInput($request->csrfParam, $request->getCsrfToken());
                        ?>
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="submit" class="btn btn-primary">确定</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="form-div">

        <form action="" class="query" onsubmit="return false">

            <?= Html::img('@web/image/icon_search.gif', [
                'width' => 26,
                'height' => 22,
                'border' => 0,
                'alt' => ''
            ]) ?>

            <?php
            $newModelArray = ['全部模型'];
            foreach ((new ArticleCat())->modelArray as $k => $val) {
                $newModelArray[$k] = $val;
            }

            echo Html::dropDownList('models', isset($_GET['models']) ? $_GET['models'] : '', $newModelArray);
            ?>

            <?= Html::decode(Html::dropDownList('cat_id', isset($_GET['cat_id']) ? $_GET['cat_id'] : null, $catList)) ?>

            文章标题
            <input type="text" name="title" size="15" placeholder="<?= isset($_GET['title']) ? $_GET['title'] : '标题关键字'; ?>">

            <input type="submit" onclick="query('<?= Yii::$app->urlManager->createUrl(['article/index']) ?>')" value=" 搜索 " class="button">

        </form>

    </div>

    <?php Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'id'
            ],

            'article_id',
            'title',
            [
                'header' => Html::a('文章分类', 'javascript:void(0);'),
                'content' => function ($model) {
                    return ArticleCat::findOne($model->cat_id)->cat_name;
                }
            ],
            [
                'header' => Html::a('模型', 'javascript:void(0);'),
                'content' => function ($model) {
                    return (new ArticleCat())->modelArray[$model->model];
                }
            ],
            [
                'header' => Html::a('文章类型', 'javascript:void(0);'),
                'content' => function ($model) {
                    $typeList = [
                        '0' => '普通',
                        '1' => '置顶'
                    ];
                    return $typeList[$model->article_type];
                }
            ],
            [
                'header' => Html::a('是否热门', 'javascript:void(0);'),
                'content' => function ($model) {
                    if ($model->is_hot == 1) {
                        return Html::img('@web/image/yes.gif', [
                            'onclick' => "setHot(this, {$model->article_id})",
                            'data-status' => 0
                        ]);
                    }
                    return Html::img('@web/image/no.gif', [
                        'onclick' => "setHot(this, {$model->article_id})",
                        'data-status' => 1
                    ]);
                }
            ],
            [
                'header' => Html::a('是否显示', 'javascript:void(0);'),
                'content' => function ($model) {
                    if ($model->status == 1) {
                        return Html::img('@web/image/yes.gif', [
                            'onclick' => "setStatus(this, {$model->article_id})",
                            'data-status' => 0
                        ]);
                    }
                    return Html::img('@web/image/no.gif', [
                        'onclick' => "setStatus(this, {$model->article_id})",
                        'data-status' => 1
                    ]);
                }
            ],
            'add_time:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Html::a('操作', 'javascript:void(0);'),
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->article_id, 'models' => $model->model], [
                            'title' => Yii::t('yii', '编辑'),
                        ]);
                    }
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>

</div>

<?php $this->beginBlock('js_end') ?>

function setHot(objs, id){
    var obj = $(objs);
    var status = obj.attr('data-status');
    $.get("<?= Url::to(['hot']) ?>", {article_id:id, status:status}, function(re){
        if (re == 1) {
            if (status == 1) {
                obj.attr({src:"<?= Url::to('@web/image/yes.gif') ?>", 'data-status':0});

            }else {
                obj.attr({src:"<?= Url::to('@web/image/no.gif') ?>", 'data-status':1});

            }
        }
    });
}

function setStatus(objs, id){
    var obj = $(objs);
    var status = obj.attr('data-status');
    $.get("<?= Url::to(['status']) ?>", {article_id:id, status:status}, function(re){
        if (re == 1) {
            if (status == 1) {
                obj.attr({src:"<?= Url::to('@web/image/yes.gif') ?>", 'data-status':0});

            }else {
                obj.attr({src:"<?= Url::to('@web/image/no.gif') ?>", 'data-status':1});

            }
        }
    });
}

/* 分类的模型begin */
get_cat();
$('.cat_ids').change(function(){
    get_cat();

});
function get_cat()
{
    var cat_id = $('.cat_ids').val();
    $.get("<?= Url::to(['articlecat/htmlradio']) ?>", {cat_id : cat_id}, function(data){
        $('.radios').html(data);
    });
}
/* 分类的模型end */

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['js_end'], $this::POS_END); ?>
