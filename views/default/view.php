<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiiplus\kvstore\Module;

$this->title = $model->section. '.' . $model->key;
$this->params['breadcrumbs'][] = ['label' => Module::t('Kvstore'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary kvstore-view">
    <div class="box-header">
        <div class="no-margin pull-left">
        <?php echo Html::a(Module::t('Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?php
            echo Html::a(
                Module::t('Delete'),
                ['delete', 'id' => $model->id],
                [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Module::t('Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]
            );
        ?>
        </div>
        <div class="no-margin pull-right"></div>
    </div>
    <div class="box-body">
    <?php
        echo DetailView::widget(
            [
                'model' => $model,
                'attributes' => [
                    'id',
                    'section',
                    'active:boolean',
                    'key',
                    'value:ntext',
                    'created:datetime',
                    'modified:datetime',
                ],
            ]
        );
    ?>
    </div>
    <div class="box-footer"></div>
</div>
