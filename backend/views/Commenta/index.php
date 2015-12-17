<?php

use common\models\Member;
use common\helpers\Functions;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = $this->params['meta_title'];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-article-index">

    <div class="main_title_wrapper clear">
        <h3><?= Html::encode($this->params['meta_title']) ?></h3>
    </div>

    <div class="form-div">

        <form action="" class="query" onsubmit="return false">

            <?= Html::img('@web/image/icon_search.gif', [
                'width' => 26,
                'height' => 22,
                'border' => 0,
                'alt' => ''
            ]) ?>

            文章ID
            <input type="text" name="aid" size="15" placeholder="<?= isset($_GET['aid']) ? $_GET['aid'] : '文章ID'; ?>">

            <input type="submit" onclick="query('<?= Yii::$app->urlManager->createUrl(['commenta/index']) ?>')" value=" 搜索 " class="button">

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

            [
                'attribute' => 'aid',
                'label' => '文章id'
            ],
            [
                'header' => Html::a('评论人', 'javascript:void(0);'),
                'content' => function ($model) {
                    if ($model->uid) {
                        return Member::findOne($model->uid)->username;
                    }
                    return '';
                }
            ],
            [
                'attribute' => 'content',
                'value' => function ($model) {
                    return Functions::truncate_utf8_string($model->content, 30);
                }
            ],
            'add_time:datetime',
            [
                'header' => Html::a('状态', 'javascript:void(0);'),
                'content' => function ($model) {
                    if ($model->status == 1) {
                        return Html::img('@web/image/yes.gif', [
                            'onclick' => "setStatus(this, {$model->id})",
                            'data-status' => 0
                        ]);
                    }
                    return Html::img('@web/image/no.gif', [
                        'onclick' => "setStatus(this, {$model->id})",
                        'data-status' => 1
                    ]);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Html::a('操作', 'javascript:void(0);'),
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>

</div>

<?php $this->beginBlock('js_end') ?>

function setStatus(objs, id){
    var obj = $(objs);
    var status = obj.attr('data-status');
    $.get("<?= Url::to(['status']) ?>", {id:id, status:status}, function(re){
        if (re == 1) {
            if (status == 1) {
                obj.attr({src:"<?= Url::to('@web/image/yes.gif') ?>", 'data-status':0});

            }else {
                obj.attr({src:"<?= Url::to('@web/image/no.gif') ?>", 'data-status':1});

            }
        }
    });
}

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['js_end'], $this::POS_END); ?>
