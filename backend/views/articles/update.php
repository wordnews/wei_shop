<?php

use yii\helpers\Html;


$this->title = $this->params['meta_title'] . $this->exTitle;

$this->params['breadcrumbs'][] = ['label' => '文章列表', 'url' => ['article/index']];
$this->params['breadcrumbs'][] = $this->params['meta_title'];
?>
<div class="article-article-update">

    <div class="main_title_wrapper clear">

        <h3><?= Html::encode($this->params['meta_title']) ?></h3>
        <p class="action">
            <?=Html::a('文章列表', ['article/index'], ['class' => 'btn btn-success']) ?>
        </p>

    </div>

    <hr>

    <?= $this->render('_form', [
        'model' => $model,
        'Article' => $Article
    ]) ?>

</div>
