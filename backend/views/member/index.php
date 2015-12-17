<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = $this->params['meta_title'] . $this->exTitle;

$this->params['breadcrumbs'][] = $this->params['meta_title'];
?>
<div class="member-index">

    <div class="main_title_wrapper clear">
        <h3><?= Html::encode($this->params['meta_title']) ?></h3>
    
        <p class="action">
            <?= Html::a('添加会员', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>

    <!-- 搜索 begin -->
    <div class="form-div">
        <form action="" class="query" onsubmit="return false">
            <?= Html::img('@web/image/icon_search.gif', [
                'width' => 26,
                'height' => 22,
                'border' => 0,
                'alt' => ''
            ]) ?>

            会员名称
            <?= Html::textInput('username', Yii::$app->request->get('username')) ?>

            <input type="submit" onclick="query('<?= Yii::$app->urlManager->createUrl(['member/index']) ?>')" value=" 搜索 " class="button">

        </form>
    </div>
    <!-- 搜索 end -->

    <?php Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'id',
                'options' => ['width' => 30]
            ],

            'uid',
            'username',
            'email',
            'score',
            'money',
            [
                'attribute' => 'reg_time',
                'value' => function($model) {
                    if ($model->reg_time > 0) {
                        return date('Y-m-d H:i', $model->reg_time);
                    } else {
                        return '';
                    }
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Html::a('操作', 'javascript:void(0);'),
                'template' => '{update} {address} {delete}',
                'buttons' => [
                    'address' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-globe"></span>', $url, [
                            'title' => Yii::t('yii', '收获地址'),
                            'aria-label' => Yii::t('yii', 'address'),
                            'data-pjax' => '0',
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>

</div>
