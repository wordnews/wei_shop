<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Area;

$this->title = $this->params['meta_title'] . $this->exTitle;

$this->params['breadcrumbs'][] = $this->params['meta_title'];
?>
<div class="member-index">

    <div class="main_title_wrapper clear">
        <h3><?= Html::encode($this->params['meta_title']) ?></h3>
    
        <p class="action">
            <?= Html::a('会员列表', ['index'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>

    <?php Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'consignee',
            [
                'header' => Html::a('地址', 'javascript:;'),
                'content' => function ($model) {
                    $area = Area::find()->where(['id' => [$model->province, $model->city, $model->district]])->asArray()->all();
                    $str = '';
                    foreach ($area as $val) {
                        $str .= $val['name'] . ' ';
                    }
                    if ($model->default == 1) {
                        $str .= '<span style="color: red">[默认]</span>';
                    }
                    $str .= '<br/>' . $model->address;
                    return $str;
                }
            ],
            [
                'header' => Html::a('联系方式', 'javascript:;'),
                'content' => function ($model) {
                    $data = '手机：' . $model->mobile . '<br/>邮编：' . $model->zipcode;
                    return $data;
                }
            ]
        ],
    ]); ?>
    <?php Pjax::end() ?>

</div>
