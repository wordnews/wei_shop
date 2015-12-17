<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="article-article-form shop-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-horizontal',
        ],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-xs-5\">{input}</div>\n{hint}\n{error}",
        ]
    ]); ?>

    <ul id="myTab" class="nav nav-tabs" role="tablist" style="margin-bottom: 20px">
        <li role="presentation" class="active">
            <a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">基本信息</a>
        </li>
        <li role="presentation" class="">
            <a href="#content" role="tab" id="content-tab" data-toggle="tab" aria-controls="content" aria-expanded="false">文章内容</a>
        </li>
    </ul>

    <div id="myTabContent" class="tab-content">
        <div role="tabpanel" class="tab-pane fade active in" id="home" aria-labelledby="home-tab">

            <?= $this->render('@backend/views/article/_form', [
                'model' => $model,
                'form' => $form,
                'Article' => $Article
            ]) ?>
            <?php $Article->model = 'article'; ?>
            <?= Html::activeHiddenInput($Article, 'model') ?>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="content" aria-labelledby="content-tab">

            <?= $form->field($model, 'content', ['template' => "<div class=\"col-xs-12\">{input}</div>",])->widget(
                \cliff363825\kindeditor\KindEditorWidget::className(),[
                'clientOptions' => [
                    'uploadJson' => Url::to(['upload/uploadeditor']),
                    'width' => '100%',
                    'height' => '350px',
                    'themeType' => 'default', // optional: default, simple, qq
                    'langType' => 'zh_CN', // optional: ar, en, ko, zh_CN, zh_TW
                ],
            ])->label(null, [
                'class' => 'col-sm-12 control-label'
            ]) ?>

        </div>

    </div>

    <div class="form-group">
        <div class="col-xs-2"></div>
        <?= Html::submitButton($Article->isNewRecord ? '确 定' : '更 新', ['class' => $Article->isNewRecord ? 'btn btn-success word-btn' : 'btn btn-primary word-btn']) ?>
        <?= Html::a('返 回', 'javascript:history.back(-1)', ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
