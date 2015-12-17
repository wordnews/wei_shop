<?php

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use common\models\Menu;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'MY-CMS',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top shop-nav',
        ],
        'innerContainerOptions' => [
            'class' => 'container-fluid'
        ]
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => (new Menu())->menuAll(),
    ]);
    ?>

    <ul id="w3" class="navbar-nav navbar-right nav">
        <li class="dropdown">
            <a class="avatar dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="true"><?= Yii::$app->user->identity->username ?><b class="caret"></b></a>
            <ul id="w4" class="dropdown-menu">
                <li>
                    <a href="<?= Url::to(['user/profile']) ?>" tabindex="-1"><span class="glyphicon glyphicon-user"></span> 修改资料</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="<?= Url::to(['public/logout']) ?>" data-method="post" tabindex="-1" data-confirm="确定要退出吗？" ><span class="glyphicon glyphicon-log-out"></span> 退出</a>
                </li>
            </ul>
        </li>
    </ul>

    <?php NavBar::end(); ?>


    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'options' => [
                'class' => 'breadcrumb shop-breadcrumb'
            ]
        ]) ?>

        <br>

        <?= Alert::widget() ?>

        <div class="content_wrapper">

            <?= $content ?>

        </div>

    </div>
</div>

<br>

<footer class="footer">
    <div class="container-fluid">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<script type="application/javascript">
    /* 鼠标移动事件 显示隐藏菜单 begin */
    var dropdown = $('.dropdown');
    dropdown.mouseover(function(){
        $(this).find('.dropdown-menu').addClass('cms_menu');
    });
    dropdown.mouseout(function(){
        $(this).find('.dropdown-menu').removeClass('cms_menu');
    });
    /* 鼠标移动事件 显示隐藏菜单 end */
</script>
