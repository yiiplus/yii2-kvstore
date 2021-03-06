<?php
/**
 * yiiplus/yii2-kvstore
 *
 * @category  PHP
 * @package   Yii2
 * @copyright 2018-2019 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-desktop/licence.txt Apache 2.0
 * @link      http://www.yiiplus.com
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiiplus\kvstore\Module;
use yiiplus\kvstore\models\Kvstore;
?>

<div class="kvstore-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->field($model, 'group')->textInput(['maxlength' => 255]); ?>
    <?php echo $form->field($model, 'key')->textInput(['maxlength' => 255]); ?>
    <?php echo $form->field($model, 'value')->textarea(['rows' => 6]); ?>
    <?php echo $form->field($model, 'description')->textInput(['maxlength' => 255]); ?>
    <?php echo $form->field($model, 'active')->checkbox(['value' => 1]); ?>
    <div class="form-group">
        <?php
            echo Html::submitButton(
                $model->isNewRecord ? Module::t('创建') : Module::t('更新'),
                [
                    'class' => $model->isNewRecord ?
                        'btn btn-success' : 'btn btn-primary'
                ]
            );
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
