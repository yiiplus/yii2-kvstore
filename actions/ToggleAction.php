<?php
/**
 * 键值存储
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    Hongbin Chen <hongbin.chen@aliyun.com>
 * @copyright 2006-2018 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-kvstore/licence.txt BSD Licence
 * @link      http://www.yiiplus.com
 */

namespace yiiplus\kvstore\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\db\Expression;
use yii\web\MethodNotAllowedHttpException;

/**
 * 动作：是否有效切换
 *
 * @category  PHP
 * @package   Yii2
 * @author    Hongbin Chen <hongbin.chen@aliyun.com>
 * @copyright 2006-2018 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-kvstore/licence.txt BSD Licence
 * @link      http://www.yiiplus.com
 */
class ToggleAction extends Action
{   
    /**
     * 模型名称
     * @var string
     */
    public $modelClass;

    /**
     * 主键ID
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * 字段名
     * @var string
     */
    public $attribute = 'active';

    /**
     * 有效
     * @var int
     */
    public $on   = 1;

    /**
     * 无效
     * @var int
     */
    public $off  = 0;
    
    /**
     * 是否有效切换
     *
     * @param integer $id 主键ID
     *
     * @return mixed
     * @throws \yii\web\MethodNotAllowedHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function run($id)
    {
        if (!Yii::$app->request->getIsPost()) {
            throw new MethodNotAllowedHttpException();
        }

        if (empty($this->modelClass) || !class_exists($this->modelClass)) {
            throw new InvalidConfigException(Yii::t('yiiplus/kvstore', '模型类不存在'));
        }
        
        $modelClass = $this->modelClass;
        $attribute = $this->attribute;
        $model = $modelClass::find()->where([$this->primaryKey => $id]);
        $model = $model->one();

        if (!$model->hasAttribute($this->attribute)) {
            throw new InvalidConfigException(Yii::t('yiiplus/kvstore', '属性不存在'));
        }

        if ($model->$attribute == $this->on) {
            $model->$attribute = $this->off;
        } elseif ($this->on instanceof Expression && $model->$attribute != $this->off) {
            $model->$attribute = $this->off;
        } else {
            $model->$attribute = $this->on;
        }

        $model->save();

        if (Yii::$app->request->getIsAjax()) {
            Yii::$app->end();
        }

        return $this->controller->redirect(Yii::$app->request->getReferrer());
    }
}
