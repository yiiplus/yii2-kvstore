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
use yii\grid\GridView;
use yiiplus\kvstore\Module;
use yiiplus\kvstore\models\Kvstore;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

$this->title = Module::t('键值存储');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary dataTables_wrapper kvstore-index">
    <div class="box-header">
        <div class="no-margin pull-left">
            <?= Html::a(Module::t('创建'), ['create'], ['class' => 'btn btn-primary']) ?>
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
    <?php
        echo GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'group',
                        'filter' => ArrayHelper::map(
                            Kvstore::find()->select('group')->distinct()->where(['<>', 'group', ''])->all(),
                            'group',
                            'group'
                        ),
                    ],
                    'key',
                    'value:ntext',
                    'description',
                    'created_at',
                    'updated_at',
                    [
                        'class' => '\yiiplus\kvstore\grid\ToggleColumn',
                        'attribute' => 'active',
                        'filter' => [1 => Yii::t('yii', '是'), 0 => Yii::t('yii', '否')],
                    ],
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]
        ); 
    ?>
    <?php Pjax::end(); ?>
    </div>
    <div class="box-footer"></div>
</div>
