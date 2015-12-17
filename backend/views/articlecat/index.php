<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = $this->params['meta_title'] . $this->exTitle;

$this->params['breadcrumbs'][] = $this->params['meta_title'];
?>
<div class="article-cat-index">

    <div class="main_title_wrapper clear">
        <h3><?= Html::encode($this->params['meta_title']) ?></h3>
    
        <p class="action">
            <?= Html::a('添加文章分类', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>

    <div id="w0" class="grid-view">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>

                <th>
                    <a href="javascript:">
                        文章分类名称
                    </a>
                </th>
                <th>
                    <a href="javascript:">
                        绑定的模型
                    </a>
                </th>
                <th>
                    <a href="javascript:">
                        描述
                    </a>
                </th>
                <th>
                    <a href="javascript:">
                        排序
                    </a>
                </th>

                <th>
                    <a href="javascript:">
                        操作
                    </a>
                </th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($list as $val): ?>
                <tr>

                    <td>
                        <?= $val['cat_name'] ?>
                    </td>
                    <td>
                        <?php
                        $datas = [];
                        foreach (unserialize($val['model']) as $value) {
                            $datas[] = $model->modelArray[$value];
                        }
                        echo implode(', ', $datas);
                        ?>
                    </td>
                    <td>
                        <?= $val['cat_desc'] ?>
                    </td>
                    <td>
                        <?= $val['sort_order'] ?>
                    </td>
                    <td>
                        <a href="<?= Url::to(['update', 'id' => $val['cat_id']]) ?>" title="更新" data-pjax="0">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                        <a href="<?= Url::to(['delete', 'id' => $val['cat_id']]) ?>" title="删除" data-confirm="您确定要删除此项吗？" data-method="post" data-pjax="0">
                            <span class="glyphicon glyphicon-trash"></span>
                        </a>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>

    </div>

</div>


