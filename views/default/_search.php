<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiiplus\kvstore\Module;
?>

<div class="kvstore-search">
    <?php $form = ActiveForm::begin(['action' => ['index'], 'method' => 'get']); ?>
    <?php echo $form->field($model, 'id'); ?>
    <?php echo $form->field($model, 'group'); ?>
    <?php echo $form->field($model, 'key'); ?>
    <?php echo $form->field($model, 'value'); ?>
    <?php echo $form->field($model, 'active')->checkbox(); ?>
    <div class="form-group">
        <?php echo Html::submitButton(Module::t('Search'), ['class' => 'btn btn-primary']); ?>
        <?php echo Html::resetButton(Module::t('Reset'), ['class' => 'btn btn-default']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
