<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = $this->params['meta_title'] . $this->exTitle;

$this->params['breadcrumbs'][] = $this->params['meta_title'];
?>
<div class="ad-position-index">

    <div class="main_title_wrapper clear">
        <h3><?= Html::encode($this->params['meta_title']) ?></h3>
    
        <p class="action">
            <?= Html::a('添加广告位', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>

    <?php Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'position_name',
            'ad_width',
            'ad_height',
            'position_desc',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Html::a('操作', 'javascript:void(0);'),
                'template' => '{ads} {update} {delete}',
                'buttons' => [
                    'ads' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['ads/index', 'position_id' => $model->position_id], [
                            'title' => Yii::t('yii', '广告'),
                            'aria-label' => Yii::t('yii', 'ads'),
                            'data-pjax' => '0',
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>

</div>
