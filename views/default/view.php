<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiiplus\kvstore\Module;

$this->title = $model->group. '.' . $model->key;
$this->params['breadcrumbs'][] = ['label' => Module::t('键值存储'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary kvstore-view">
    <div class="box-header">
        <div class="no-margin pull-left">
        <?php echo Html::a(Module::t('更新'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?php
            echo Html::a(
                Module::t('删除'),
                ['delete', 'id' => $model->id],
                [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Module::t('确定要删除该项目吗?'),
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
            echo '<tr><th>'. Module::t('驱动') .'</th><td>' . $cacheName . '</td></tr>';
            echo '<tr><th>'. Module::t('缓存键') .'</th><td>' . $cacheKey . '</td></tr>';
            echo '<tr><th>'. Module::t('缓存值') .'</th><td>' . $cacheValue . '</td></tr>';
            echo '</tbody></table>';
        }
    ?>
    </div>
    <div class="box-footer"></div>
</div>
