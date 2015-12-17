<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Role;

$this->title = $this->params['meta_title'] . $this->exTitle;

$this->params['breadcrumbs'][] = $this->params['meta_title'];
?>
<div class="user-index">

    <div class="main_title_wrapper clear">
        <h3><?= Html::encode($this->params['meta_title']) ?></h3>
    
        <p class="action">
            <?= Html::a('添加管理员', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>

    <?php Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
                'options' => [
//                    'width' => 80
                ]
            ],
            [
                'attribute' => 'username',
                'label' => '管理员账号'
            ],
            [
                'attribute' => 'role_id',
                'label' => '角色',
                'value' => function($model){
                    return Role::findOne($model->role_id)->role_name;
                }
            ],
            [
                'attribute' => 'created_at',
                'label' => '创建时间',
                'value' => function ($model) {
                    return date('Y-m-d H:i', $model->created_at);
                }
            ],
            [
                'header' => Html::a('状态', 'javascript:;'),
                'content' => function ($model) {
                    if ($model->status == 1) {
                        return Html::img('@web/image/yes.gif', [
                            'onclick' => "setStatus(this, {$model->id})",
                            'data-status' => 0
                        ]);
                    } else {
                        return Html::img('@web/image/no.gif', [
                            'onclick' => "setStatus(this, {$model->id})",
                            'data-status' => 1
                        ]);
                    }
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