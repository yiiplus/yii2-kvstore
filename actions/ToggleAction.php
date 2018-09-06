<?php

namespace yiiplus\kvstore\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\db\Expression;
use yii\web\MethodNotAllowedHttpException;

class ToggleAction extends Action
{
    /**
     * @var string name of the model
     */
    public $modelClass;

    /**
     * @var string model attribute
     */
    public $attribute = 'active';

    /**
     * @var string scenario model
     */
    public $scenario = null;

    /**
     * @var string|array additional condition for loading the model
     */
    public $andWhere;

    /**
     * @var string|int|boolean|Expression what to set active models to
     */
    public $onValue = 1;

    /**
     * @var string|int|boolean what to set inactive models to
     */
    public $offValue = 0;

    /**
     * @var string|array URL to redirect to
     */
    public $redirect;

    /**
     * @var string pk field name
     */
    public $primaryKey = 'id';

    /**
     * Run the action
     * @param $id integer id of model to be loaded
     *
     * @throws \yii\web\MethodNotAllowedHttpException
     * @throws \yii\base\InvalidConfigException
     * @return mixed
     */
    public function run($id)
    {
        if (!Yii::$app->request->getIsPost()) {
            throw new MethodNotAllowedHttpException();
        }
        $id = (int)$id;
        $result = null;

        if (empty($this->modelClass) || !class_exists($this->modelClass)) {
            throw new InvalidConfigException("Model class doesn't exist");
        }
        
        $modelClass = $this->modelClass;
        $attribute = $this->attribute;
        $model = $modelClass::find()->where([$this->primaryKey => $id]);

        if (!empty($this->andWhere)) {
            $model->andWhere($this->andWhere);
        }

        $model = $model->one();

        if (!is_null($this->scenario)) {
            $model->scenario = $this->scenario;
        }

        if (!$model->hasAttribute($this->attribute)) {
            throw new InvalidConfigException("Attribute doesn't exist");
        }

        if ($model->$attribute == $this->onValue) {
            $model->$attribute = $this->offValue;
        } elseif ($this->onValue instanceof Expression && $model->$attribute != $this->offValue) {
            $model->$attribute = $this->offValue;
        } else {
            $model->$attribute = $this->onValue;
        }

        $model->save();

        if (Yii::$app->request->getIsAjax()) {
            Yii::$app->end();
        }

        if (!empty($this->redirect)) {
            return $this->controller->redirect($this->redirect);
        }

        return $this->controller->redirect(Yii::$app->request->getReferrer());
    }
}
