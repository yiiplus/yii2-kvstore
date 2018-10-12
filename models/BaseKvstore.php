<?php

namespace yiiplus\kvstore\models;

use Yii;
use yii\helpers\Json;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\base\InvalidParamException;
use yii\behaviors\TimestampBehavior;

class BaseKvstore extends ActiveRecord implements KvstoreInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yp_kvstore}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['section', 'key', 'description'], 'string', 'max' => 255],
            [
                ['key'],
                'unique',
                'targetAttribute' => ['section', 'key'],
            ],
            [['created_at', 'updated_at'], 'safe'],
            [['active'], 'boolean'],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Yii::$app->kvstore->clearCache();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->kvstore->clearCache();
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getkvstore()
    {
        $kvstore = static::find()->where(['active' => true])->asArray()->all();
        return ArrayHelper::map($kvstore, 'key', 'value', 'section');
    }

    /**
     * @inheritdoc
     */
    public function setKvstore($section, $key, $value)
    {
        $model = static::findOne(['section' => $section, 'key' => $key]);

        if ($model === null) {
            $model = new static();
            $model->active = 1;
        }
        $model->section = $section;
        $model->key = $key;
        $model->value = strval($value);

        return $model->save();
    }

    /**
     * @inheritdoc
     */
    public function activateKvstore($section, $key)
    {
        $model = static::findOne(['section' => $section, 'key' => $key]);

        if ($model && $model->active == 0) {
            $model->active = 1;
            return $model->save();
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function deactivateKvstore($section, $key)
    {
        $model = static::findOne(['section' => $section, 'key' => $key]);

        if ($model && $model->active == 1) {
            $model->active = 0;
            return $model->save();
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function deleteKvstore($section, $key)
    {
        $model = static::findOne(['section' => $section, 'key' => $key]);

        if ($model) {
            return $model->delete();
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteAllKvstore()
    {
        return static::deleteAll();
    }

    /**
     * @inheritdoc
     */
    public function findKvstore($key, $section = null)
    {
        if (is_null($section)) {
            $pieces = explode('.', $key, 2);
            if (count($pieces) > 1) {
                $section = $pieces[0];
                $key = $pieces[1];
            } else {
                $section = '';
            }
        }
        return $this->find()->where(['section' => $section, 'key' => $key])->limit(1)->one();
    }
}
