<?php

use yii\helpers\Html;


$this->title = $this->params['meta_title'];

$this->params['breadcrumbs'][] = ['label' => '文章评论', 'url' => ['index']];

$this->params['breadcrumbs'][] = '编辑评论';
?>
<div class="comment-article-update">

    <div class="main_title_wrapper clear">

        <h3><?= Html::encode($this->params['meta_title']) ?></h3>
        <p class="action">
            <?=Html::a('评论列表', ['index'], ['class' => 'btn btn-success']) ?>
        </p>

    </div>

    <hr>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
