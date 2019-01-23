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

namespace yiiplus\kvstore\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\caching\Cache;
use yiiplus\kvstore\models\Kvstore;
use yiiplus\kvstore\models\KvstoreSearch;
use yiiplus\kvstore\actions\ToggleAction;
use yiiplus\kvstore\Module;

/**
 * 键值存储管理
 *
 * @category  PHP
 * @package   Yii2
 * @author    Hongbin Chen <hongbin.chen@aliyun.com>
 * @copyright 2006-2018 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-kvstore/licence.txt BSD Licence
 * @link      http://www.yiiplus.com
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'toggle' => [
                'class' => ToggleAction::className(),
                'modelClass' => 'yiiplus\kvstore\models\Kvstore',
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new KvstoreSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render(
            'index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->kvstore->cache instanceof Cache) {
            $model = $this->findModel($id);
            $cacheKey   = Yii::$app->kvstore->cachePrefix . $model->group . '_' . $model->key;
            $cacheValue = Yii::$app->kvstore->cache->get($cacheKey);
            $params = [
                'model' => $model,
                'cacheName'  => get_class(Yii::$app->kvstore->cache),
                'cacheKey'   => $cacheKey,
                'cacheValue' => $cacheValue,
            ];
        } else {
            $params = [
                'model' => $this->findModel($id),
            ];
        }

        return $this->render('view', $params);
    }

    public function actionCreate()
    {
        $model = new Kvstore(['active' => 1]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    public function actionDelete($id)
    {
        if (Yii::$app->request->isPost) {
            $this->findModel($id)->delete();
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Kvstore::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Module::t('请求页不存在'));
    }
}
