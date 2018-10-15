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

/**
 * 键值存储基础动作
 *
 * @category  PHP
 * @package   Yii2
 * @author    Hongbin Chen <hongbin.chen@aliyun.com>
 * @copyright 2006-2018 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-kvstore/licence.txt BSD Licence
 * @link      http://www.yiiplus.com
 */
class KvstoreAction extends Action
{
    /**
     * 模型名称
     */
    public $modelClass;

    /**
     * 视图名称
     */
    public $viewName = 'kvstore';

    /**
     * 场景
     */
    public $scenario;

    /**
     * 键值存储基础动作
     */
    public function run()
    {
        $model = new $this->modelClass();

        if ($this->scenario) {
            $model->setScenario($this->scenario);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($model->toArray() as $key => $value) {
                Yii::$app->kvstore->set($key, $value, $model->formName());
            }
        }

        foreach ($model->attributes() as $key) {
            $model->{$key} = Yii::$app->kvstore->get($key, $model->formName());
        }

        return $this->controller->render($this->viewName, ['model' => $model]);
    }
}
