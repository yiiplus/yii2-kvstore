<?php
namespace yiiplus\kvstore\actions;

use Yii;
use yii\base\Action;

class KvstoreAction extends Action
{
    /**
     * @var string class name of the model which will be used to validate the attributes.
     * The class should have a scenario matching the `scenario` variable.
     * The model class must implement [[Model]].
     * This property must be set.
     */
    public $modelClass;

    /**
     * @var string The scenario this model should use to make validation
     */
    public $scenario;

    /**
     * @var string the name of the view to generate the form. Defaults to 'kvstore'.
     */
    public $viewName = 'kvstore';

    /**
     * Render the kvstore form.
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
            Yii::$app->getSession()->addFlash('success',
                Module::t('Successfully saved kvstore on {section}',
                    ['section' => $model->formName()]
                )
            );
        }

        foreach ($model->attributes() as $key) {
            $model->{$key} = Yii::$app->kvstore->get($key, $model->formName());
        }

        return $this->controller->render($this->viewName, ['model' => $model]);
    }
}
