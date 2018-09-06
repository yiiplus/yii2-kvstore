<?php
use yii\helpers\Html;
use yiiplus\kvstore\Module;

$this->title = Module::t('Create');
$this->params['breadcrumbs'][] = ['label' => Module::t('Kvstore'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary kvstore-create">
    <div class="box-header"></div>
    <div class="box-body">
    <?=
    $this->render(
        '_form',
        [
            'model' => $model,
        ]
    ) ?>
    </div>
    <div class="box-footer"></div>
</div>
