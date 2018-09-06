<?php

use yii\helpers\Html;
use yiiplus\kvstore\Module;

$this->title = Module::t('Update') . ':' . $model->section. '.' . $model->key;

$this->params['breadcrumbs'][] = ['label' => Module::t('Kvstore'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->section. '.' . $model->key, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('Update');

?>
<div class="box box-primary dataTables_wrapper kvstore-update">
    <div class="box-header">
        <div class="no-margin pull-left"></div>
        <div class="no-margin pull-right"></div>
    </div>
    <div class="box-body">
        <?php echo $this->render('_form', ['model' => $model]); ?>
    </div>
    <div class="box-footer"></div>
</div>
