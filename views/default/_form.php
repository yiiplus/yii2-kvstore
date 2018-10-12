<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiiplus\kvstore\Module;
use \yiiplus\kvstore\models\Kvstore;
?>

<div class="kvstore-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'section')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'key')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'active')->checkbox(['value' => 1]) ?>
    <div class="form-group">
        <?=
        Html::submitButton(
            $model->isNewRecord ? Module::t('Create') :
                Module::t('Update'),
            [
                'class' => $model->isNewRecord ?
                    'btn btn-success' : 'btn btn-primary'
            ]
        ) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
