<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="role-form shop-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-horizontal',
        ],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-xs-5\">{input}</div>\n{hint}\n{error}",
        ]
    ]); ?>

    <?= $form->field($model, 'role_name')->textInput(['maxlength' => true])->label(null, [
        'class' => 'col-sm-2 control-label'
    ]) ?>

    <?= $form->field($model, 'role_descript')->textarea()->label(null, [
        'class' => 'col-sm-2 control-label'
    ]) ?>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th colspan="2">角色权限控制</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($treeRoleList as $k => $role): ?>
            <tr>
                <td width="20%">
                    <input onchange="checkAll(this, '<?= $k ?>')" type="checkbox" >
                    <?= $role['action_title'] ?>
                </td>
                <td width="80%">
                    <?php foreach ($role['_child'] as $item): ?>
                        <div class="col-xs-2" style="padding-top: 2px; padding-bottom: 2px;">
                        <input type="checkbox" class="check-box<?= $k ?>" name="actions_list[]" value="<?= $item['action_code'] ?>" <?= $model->isChecked($model->action_list, $item['action_code']) ? 'checked' : '' ?> >
                        <?= $item['action_title'] ?>
                        </div>
                        <?php foreach ($item['_child'] as $val) { ?>
                            <div class="col-xs-2" style="padding-top: 2px; padding-bottom: 2px;">
                                <input type="checkbox" class="check-box<?= $k ?>" name="actions_list[]" value="<?= $val['action_code'] ?>" <?= $model->isChecked($model->action_list, $val['action_code']) ? 'checked' : '' ?> >
                                <?= $val['action_title'] ?>
                            </div>
                        <?php } ?>
                        <br/><br/>
                    <?php endforeach; ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

    <div class="form-group">
        <div class="col-xs-2"></div>
        <?= Html::submitButton($model->isNewRecord ? '确 定' : '更 新', ['class' => $model->isNewRecord ? 'btn btn-success word-btn' : 'btn btn-primary word-btn']) ?>
        <?= Html::a('返 回', 'javascript:history.back(-1)', ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="application/javascript">
    /**
     * 全选/全不选状态
     */
    function checkAll(obj, n)
    {
        var checkStatus = obj.checked;
        $('.check-box' + n).each(function(){
            this.checked = checkStatus;
        });
    }
</script>
