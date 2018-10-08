<?php

namespace yiiplus\kvstore\models;

use Yii;
use yiiplus\kvstore\Module;

class Kvstore extends BaseKvstore
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['section', 'key'], 'string', 'max' => 255],
            [
                ['key'],
                'unique',
                'targetAttribute' => ['section', 'key'],
                'message' => Module::t('{attribute} "{value}" already exists for this section.')
            ],
            [['created', 'modified'], 'safe'],
            [['active'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => Module::t('ID'),
            'section'   => Module::t('Section'),
            'key'       => Module::t('Key'),
            'value'     => Module::t('Value'),
            'active'    => Module::t('Active'),
            'created'   => Module::t('Created'),
            'modified'  => Module::t('Modified'),
        ];
    }
}
