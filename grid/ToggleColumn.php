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

namespace yiiplus\kvstore\grid;

use Yii;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\web\View;
use yiiplus\kvstore\Module;

/**
 * 数据列：是否有效切换
 *
 * @author Hongbin Chen <hongbin.chen@aliyun.com>
 * @since 2.0.0
 */
class ToggleColumn extends DataColumn
{
    public $action = 'toggle';
    
    public $primaryKey = 'primaryKey';

    public $enableAjax = true;

    public $iconOn = 'ok';

    public $iconOff = 'remove';

    public $onText;

    public $offText;
    
    public $displayValueText = false;
    
    public $onValueText;
    
    public $offValueText;
    

    public function init()
    {
        if ($this->onText === null) {
            $this->onText = Module::t('On');
        }
        if ($this->offText === null) {
            $this->offText = Module::t('Off');
        }
        if ($this->onValueText === null) {
            $this->onValueText = Module::t('Active');
        }
        if ($this->offValueText === null) {
            $this->offValueText = Module::t('Inactive');
        }
        if ($this->enableAjax) {
            $this->registerJs();
        }
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        $url = [$this->action, 'id' => $model->{$this->primaryKey}];

        $attribute = $this->attribute;
        $value = $model->$attribute;

        if ($value === null || $value == true) {
            $icon = $this->iconOn;
            $title = $this->offText;
            $valueText = $this->onValueText;
        } else {
            $icon = $this->iconOff;
            $title = $this->onText;
            $valueText = $this->offValueText;
        }
        return Html::a(
            '<span class="glyphicon glyphicon-' . $icon . '"></span>',
            $url,
            [
                'title' => $title,
                'class' => 'toggle-column',
                'data-method' => 'post',
                'data-pjax' => '0',
            ]
        ) . ( $this->displayValueText ? " {$valueText}" : "" );
    }

    public function registerJs()
    {
        if(Yii::$app->request->isAjax) {
            return;
        }
        $js = <<<'JS'
$(document.body).on("click", "a.toggle-column", function(e) {
    e.preventDefault();
    $.post($(this).attr("href"), function(data) {
        var pjaxId = $(e.target).closest("[data-pjax-container]").attr("id");
        $.pjax.reload({container:"#" + pjaxId});
    });
    return false;
});
JS;
        $this->grid->view->registerJs($js, View::POS_READY, 'toggle-column');
    }
}
