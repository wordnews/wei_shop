<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\User;
use dosamigos\datetimepicker\DateTimePicker;

$this->title = $this->params['meta_title'];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-log-index">

    <div class="main_title_wrapper clear">
        <h3><?= Html::encode($this->params['meta_title']) ?></h3>
    </div>

    <div class="form-div">

        <form action="" class="query" onsubmit="return false">

            <?= Html::img('@web/image/icon_search.gif', [
                'width' => 26,
                'height' => 22,
                'border' => 0,
                'alt' => '',
                'style' => 'float: left'
            ]) ?>

            <?= Html::dropDownList('action', isset($_GET['action']) ? $_GET['action'] : '', [
                '选择动作...',
                'add' => '新增',
                'edit' => '编辑',
                'delete' => '删除'
            ], ['style' => 'float: left; margin-top: 3px; margin-left: 2px']);
            ?>
            <?php
                if ($time = Yii::$app->request->get('UserLog')) {
                    $model->first_time = $time['first_time'];
                    $model->last_time = $time['last_time'];
                }
            ?>
            <div style="float: left; width: 120px; margin-left: 3px; margin-top: -4px">
            <?= DateTimePicker::widget([
                'model' => $model,
                'attribute' => 'first_time',
                'language' => 'zh-CN',
                'size' => 'ms',
                'template' => '{input}',
                'pickButtonIcon' => 'glyphicon glyphicon-time',
                'clientOptions' => [
                    'startView' => 2,
                    'minView' => 2,
                    'maxView' => 5,
                    'autoclose' => true,
                    'linkFormat' => 'yyyy-mm-dd',
                    'format' => 'yyyy-mm-dd',
                    'todayBtn' => false
                ]
            ]);?>
            </div>
            <div style="float: left; width: 120px; margin-left: 3px; margin-top: -4px">
            <?= DateTimePicker::widget([
                'model' => $model,
                'attribute' => 'last_time',
                'language' => 'zh-CN',
                'size' => 'ms',
                'template' => '{input}',
                'pickButtonIcon' => 'glyphicon glyphicon-time',
                'clientOptions' => [
                    'startView' => 2,
                    'minView' => 2,
                    'maxView' => 5,
                    'autoclose' => true,
                    'linkFormat' => 'yyyy-mm-dd',
                    'format' => 'yyyy-mm-dd',
                    'todayBtn' => false
                ]
            ]);?>
            </div>&nbsp;

            <input type="submit" onclick="query('<?= Yii::$app->urlManager->createUrl(['userlog/index']) ?>')" value=" 搜索 " class="button">

        </form>

    </div>

    <?php Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showFooter' => true,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'id',
                'footer' => Html::button('批量删除', [
                    'class' => 'btn btn-danger',
                    'onclick' => 'delAll()'
                ]),
                'options' => [
                    'width' => 50
                ]
            ],
            [
                'attribute' => 'uid',
                'label' => '管理员ID',
            ],
            [
                'header' => Html::a('管理员账号', 'javascript:;'),
                'content' => function($model){
                    return User::findOne($model->uid)->username;
                },
            ],
            [
                'attribute' => 'action_ip',
                'label' => '操作IP',
            ],
            'model',
            'model_id',
            'remark',
            'add_time:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Html::a('操作', 'javascript:void(0);'),
                'template' => '{delete}',
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>

</div>


<?php $this->beginBlock('js_end') ?>

function delAll()
{
    var id = [];
    $('input[name="id[]"]').each(function(e){
        if (this.checked == true) {
            id[e] = $(this).val();
        }
    });
    if (id != '') {
        $.get('<?= \yii\helpers\Url::to(['delall']) ?>', {id:id}, function(data){
            location.reload();
        });
    } else {
        alert('请选择要删除的数据！');
    }
}

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['js_end'], $this::POS_END); ?>
