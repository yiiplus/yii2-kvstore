<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiiplus\kvstore\Module;

$this->title = $model->group. '.' . $model->key;
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
        if(isset($cacheName)) {
            echo '<h3>Database:</h3>';
        }

        echo DetailView::widget(
            [
                'model' => $model,
                'attributes' => [
                    'id',
                    'group',
                    'active:boolean',
                    'key',
                    'value:ntext',
                    'description',
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]
        );

        if(isset($cacheName)) {
            echo '<h3>Cache:</h3>';
            echo '<table id="w0" class="table table-striped table-bordered detail-view"><tbody>';
            echo '<tr><th>驱动</th><td>' . $cacheName . '</td></tr>';
            echo '<tr><th>缓存键</th><td>' . $cacheKey . '</td></tr>';
            echo '<tr><th>缓存值</th><td>' . $cacheValue . '</td></tr>';
            echo '</tbody></table>';
        }
    ?>
    </div>
    <div class="box-footer"></div>
</div>
