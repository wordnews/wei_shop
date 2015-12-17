<?php
use yii\helpers\Html;
?>
<?= $form->field($Article, 'title')->textInput(['maxlength' => 150])->label(null, [
    'class' => 'col-sm-2 control-label'
]) ?>

<?php if ($Article->article_type === null) $Article->article_type = 0; ?>
<?= $form->field($Article, 'article_type')->radioList([
    '0' => '普通',
    '1' => '置顶'
], [
    'style' => 'margin-top:7px'
])->label(null, [
    'class' => 'col-sm-2 control-label'
]) ?>

<?php if ($Article->status === null) $Article->status = 1; ?>
<?= $form->field($Article, 'status')->radioList([
    '1' => '是',
    '0' => '否'
], [
    'style' => 'margin-top:7px'
])->label(null, [
    'class' => 'col-sm-2 control-label'
]) ?>


<?php if ($Article->is_hot === null) $Article->is_hot = 0; ?>
<?= $form->field($Article, 'is_hot')->radioList([
    '1' => '是',
    '0' => '否'
], [
    'style' => 'margin-top:7px'
])->label(null, [
    'class' => 'col-sm-2 control-label'
]) ?>

<?= $form->field($Article, 'author')->textInput(['maxlength' => 30])->label(null, [
    'class' => 'col-sm-2 control-label'
]) ?>

<?= $form->field($Article, 'keywords')->textInput(['maxlength' => 255])->label(null, [
    'class' => 'col-sm-2 control-label'
]) ?>

<?= $form->field($Article, 'description')->textarea([
    'rows' => 3
])->label(null, [
    'class' => 'col-sm-2 control-label'
]) ?>

<?php if (!$Article->cat_id)  $Article->cat_id = Yii::$app->request->get('cat_id', 0); ?>
<?= Html::activeHiddenInput($Article, 'cat_id') ?>

