<?php

use yii\helpers\Html;
use yiiplus\kvstore\Module;

$this->title = Module::t('更新') . ':' . $model->group. '.' . $model->key;

$this->params['breadcrumbs'][] = ['label' => Module::t('键值存储'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->group. '.' . $model->key, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('更新');

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
