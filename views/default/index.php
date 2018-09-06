<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yiiplus\kvstore\Module;
use yiiplus\kvstore\models\Kvstore;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

$this->title = Module::t('Kvstore');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary dataTables_wrapper kvstore-index">
    <div class="box-header">
        <div class="no-margin pull-left">
            <?= Html::a(Module::t('Create'), ['create'], ['class' => 'btn btn-primary']) ?>
        </div>
        <div class="no-margin pull-right">
            <button type="button" class="btn btn-default"><i class="fa fa-cog"></i></button>
            <button type="button" class="btn btn-default"><i class="fa fa-refresh"></i></button>
            <button type="button" class="btn btn-default"><i class="fa fa-save"></i></button>
            <button type="button" class="btn btn-default"><i class="fa fa-arrows-alt"></i></button>
        </div>
    </div>
    <div class="box-body">
    <?php Pjax::begin(); ?>
    <?=
    GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'id',
                //'type',
                [
                    'attribute' => 'section',
                    'filter' => ArrayHelper::map(
                        Kvstore::find()->select('section')->distinct()->where(['<>', 'section', ''])->all(),
                        'section',
                        'section'
                    ),
                ],
                'key',
                'value:ntext',
                [
                    'class' => '\yiiplus\kvstore\grid\ToggleColumn',
                    'attribute' => 'active',
                    'filter' => [1 => Yii::t('yii', 'Yes'), 0 => Yii::t('yii', 'No')],
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]
    ); ?>
    <?php Pjax::end(); ?>
    </div>
    <div class="box-footer"></div>
</div>
