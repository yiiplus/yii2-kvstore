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

namespace yiiplus\kvstore\models;

use Yii;
use yii\helpers\Json;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\base\InvalidParamException;
use yii\behaviors\TimestampBehavior;
use yiiplus\kvstore\Module;

/**
 * Kvstore
 *
 * @author Hongbin Chen <hongbin.chen@aliyun.com>
 * @since 2.0.0
 */
class Kvstore extends ActiveRecord implements KvstoreInterface
{
    public static function tableName()
    {
        return '{{%yp_kvstore}}';
    }

    public function rules()
    {
        return [
            [['value'], 'string'],
            [['group', 'key', 'description'], 'string', 'max' => 255],
            [
                ['key'],
                'unique',
                'targetAttribute' => ['group', 'key'],
                'message' => Module::t('{attribute} "{value}" already exists for this group.')
            ],
            [['created_at', 'updated_at'], 'safe'],
            [['active'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'          => Module::t('ID'),
            'group'       => Module::t('Group'),
            'key'         => Module::t('Key'),
            'value'       => Module::t('Value'),
            'description' => Module::t('Description'),
            'active'      => Module::t('Active'),
            'created_at'  => Module::t('CreatedAt'),
            'updated_at'  => Module::t('UpdatedAt'),
        ];
    }

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

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Yii::$app->kvstore->clearCache($this->group, $this->key);
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->kvstore->clearCache($this->group, $this->key);
    }

    public function getkvstore($group, $key)
    {
        $data = static::find()->select('value')->where(['group' => $group, 'key' => $key, 'active' => true])->limit(1)->one();
        if($data) {
            return $data->value;
        }
        return false;
    }

    public function setKvstore($group, $key, $value)
    {
        $model = static::findOne(['group' => $group, 'key' => $key]);

        if ($model === null) {
            $model = new static();
            $model->active = 1;
        }
        $model->group = $group;
        $model->key = $key;
        $model->value = strval($value);

        return $model->save();
    }

    public function activateKvstore($group, $key)
    {
        $model = static::findOne(['group' => $group, 'key' => $key]);

        if ($model && $model->active == 0) {
            $model->active = 1;
            return $model->save();
        }
        return false;
    }

    public function deactivateKvstore($group, $key)
    {
        $model = static::findOne(['group' => $group, 'key' => $key]);

        if ($model && $model->active == 1) {
            $model->active = 0;
            return $model->save();
        }
        return false;
    }

    public function deleteKvstore($group, $key)
    {
        $model = static::findOne(['group' => $group, 'key' => $key]);

        if ($model) {
            return $model->delete();
        }
        return true;
    }
}
