<?php
/**
 * yiiplus/yii2-desktop
 *
 * @category  PHP
 * @package   Yii2
 * @copyright 2018-2019 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-desktop/licence.txt Apache 2.0
 * @link      http://www.yiiplus.com
 */

namespace yiiplus\kvstore\actions;

use Yii;
use yii\base\Action;

/**
 * 键值存储基础动作
 *
 * @author Hongbin Chen <hongbin.chen@aliyun.com>
 * @since 2.0.0
 */
class KvstoreAction extends Action
{
    /**
     * 模型名称
     */
    public $modelClass;

    /**
     * 分组
     */
    public $group = null;

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
        // 模型实例化
        $model = new $this->modelClass();
        
        // 场景设置
        if ($this->scenario) {
            $model->setScenario($this->scenario);
        }

        // 分组设置
        if ($this->group === null) {
            $this->group = $model->formName();
        }

        // kvStore 保存
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($model->toArray() as $key => $value) {
                Yii::$app->kvstore->set($key, $value, $this->group);
            }
            $this->controller->redirect(Yii::$app->request->getUrl());
            return;
        }
        
        // kvStore 查询
        foreach ($model->attributes() as $key) {
            $model->{$key} = Yii::$app->kvstore->get($key, $this->group);
        }

        return $this->controller->render($this->viewName, ['model' => $model]);
    }
}
