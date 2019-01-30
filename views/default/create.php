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
use yiiplus\kvstore\Module;

$this->title = Module::t('创建');
$this->params['breadcrumbs'][] = ['label' => Module::t('键值存储'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary kvstore-create">
    <div class="box-header"></div>
    <div class="box-body">
        <?php echo $this->render('_form',['model' => $model]); ?>
    </div>
    <div class="box-footer"></div>
</div>