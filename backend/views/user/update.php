<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $this->params['meta_title'] . $this->exTitle;

$this->params['breadcrumbs'][] = ['label' => '管理员列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->params['meta_title'];
?>
<div class="user-create">

    <div class="main_title_wrapper clear">

        <h3><?= Html::encode($this->params['meta_title']) ?></h3>
        <p class="action">
            <?=Html::a('管理员列表', ['index'], ['class' => 'btn btn-success']) ?>
        </p>

    </div>

    <hr>

    <div class="user-form shop-form">

        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'form-horizontal',
            ],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-xs-5\">{input}</div>\n{hint}\n{error}",
            ]
        ]); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label(null, [
            'class' => 'col-sm-2 control-label'
        ]) ?>

        <?= $form->field($model, 'password')->textInput(['maxlength' => true])->label(null, [
            'class' => 'col-sm-2 control-label'
        ])->hint('留空表示不修改密码') ?>

        <?= $form->field($model, 'role_id')->dropDownList(\yii\helpers\ArrayHelper::map($role_list, 'role_id', 'role_name'), ['prompt' => '请选择...'])->label(null, [
            'class' => 'col-sm-2 control-label'
        ]) ?>

        <div class="form-group">
            <div class="col-xs-2"></div>
            <?= Html::submitButton('编 辑', ['class' => 'btn btn-primary word-btn', 'name' => 'signup-button']) ?>
            <?= Html::a('返 回', 'javascript:history.back(-1)', ['class' => 'btn btn-warning']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
