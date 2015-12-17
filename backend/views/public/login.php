<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>CMS管理面板</title>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?= Html::cssFile('@web/assets/admin_assets/css/login.css') ?>
</head>
<body>
<div class="login">
    <h1><a href="javascript:;">CMS</a></h1>
        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'clearfix'
            ]
        ]) ?>
        <p>
            <label>管理员帐号：<?= Html::activeInput('text', $model, 'username', ['class' => 'input'])?></label>
            <span style="color: red"><?php if ($error = $model->getErrors('username')) { echo $error[0]; } ?></span>
        </p>
        <p>
            <label>管理员密码：<?= Html::activeInput('password', $model, 'password', ['class' => 'input'])?></label>
            <span style="color: red"><?php if ($errorPassword = $model->getErrors('password')) { echo $errorPassword[0]; } ?></span>
        </p>
        <p class="submit">
<!--            <a href="javascript:;" class="forgot">忘记密码?</a>-->
            <input type="submit" value="登入后台" class="button btn_3" />
            <label class="forgot" style="margin-top: 3px"><?= Html::activeCheckbox($model, 'rememberMe') ?></label>
        </p>
    <?php ActiveForm::end(); ?>
</div>
</body>
</html>