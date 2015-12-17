<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="article-download-form shop-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data'
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
            <a href="#content" role="tab" id="content-tab" data-toggle="tab" aria-controls="content" aria-expanded="false">详细信息</a>
        </li>
    </ul>

    <div id="myTabContent" class="tab-content">
        <div role="tabpanel" class="tab-pane fade active in" id="home" aria-labelledby="home-tab">

            <?= $this->render('@backend/views/article/_form', [
                'model' => $model,
                'form' => $form,
                'Article' => $Article
            ]) ?>
            <?php $Article->model = 'download'; ?>
            <?= Html::activeHiddenInput($Article, 'model') ?>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="content" aria-labelledby="content-tab">

            <?= $form->field($model, 'file_path')->fileInput([
                'style' => 'margin-top:7px'
            ])->label(null, [
                'class' => 'col-sm-2 control-label'
            ]) ?>
            <?php if($model->file_path): ?>
                <div class="show_image" style="margin-bottom: 5px">
                <?= Html::activeHiddenInput($model, 'file_path') ?>
                <div class="col-xs-offset-2" style="margin-bottom: 5px">
                    <span class="word-btn"></span>
                    <?= $model->file_path ?>
                    <a href="javascript:;" style="color: red;" title="删除" onclick="delImage(<?= $model->id ?>)">[-]</a>
                </div>
                </div>
            <?php endif ?>

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
        <?= Html::submitButton($model->isNewRecord ? '确 定' : '更 新', ['class' => $model->isNewRecord ? 'btn btn-success word-btn' : 'btn btn-primary word-btn']) ?>
        <?= Html::a('返 回', 'javascript:history.back(-1)', ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php $this->beginBlock('js_end') ?>

function delImage(id)
{
    if (confirm('确定要删除当前文件吗？')) {
        $.get("<?= Url::to(['delfile']) ?>", {id:id}, function (re) {
            if (re == 1) {
                $('.show_image').remove();
            }else {
                alert('删除失败了o(╯□╰)o');
            }
        });
    }
}

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['js_end'], $this::POS_END); ?>
