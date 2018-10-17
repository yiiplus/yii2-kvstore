yii2 kvstore
=============
在YiiPlus中提供了kvstore存储的方式来对数据量大的情况下进行优化。kvstore 是以 key->value 数据结构进行存储.

1.安装
------------

安装此扩展的首选方法是通过 [composer](http://getcomposer.org/download/).

执行命令

```bash
php composer.phar require --prefer-dist yiiplus/yii2-kvstore "^2.0.0"
```

或添加配置到项目目录下的composer.json

```
"require": {
    ...
    "yiiplus/yii2-kvstore": "^2.0.0",
    ...
}
```

2.配置
------------

```php
'modules' => [
    'kvstore' => [
        'class' => 'yiiplus\kvstore\Module',
        'sourceLanguage' => 'en'
    ],
    ...
],

...

'components' => [
    'i18n' => [
        'translations' => [
            '*' => [
                'class' => 'yii\i18n\PhpMessageSource'
            ],
        ],
    ],
    'kvstore' => [
        'class' => 'yiiplus\kvstore\Kvstore'
    ],
    ...
]
```

3.创建数据
------------

```bash
./yii migrate --migrationPath=@yiiplus/kvstore/migrations
```

4.使用
------------

#### 快速使用

```php
$kvstore = Yii::$app->kvstore;

$value = $kvstore->get('group.key');
$value = $kvstore->get('key', 'group');

$kvstore->set('group.key', 'value');
$kvstore->set('group.key', 'value');
$kvstore->set('key', 'value', 'group');
```

#### 自定义MVC模型

```php
// Model
class Site extends Model {
    public $siteName, $siteDescription;
    public function rules()
    {
        return [
            [['siteName', 'siteDescription'], 'string'],
        ];
    }
    
    public function fields()
    {
            return ['siteName', 'siteDescription'];
    }
    
    public function attributes()
    {
            return ['siteName', 'siteDescription'];
    }
}

//Controller
function actions(){
   return [
        ....
            'site-kvstore' => [
                'class' => 'yiiplus\kvstore\KvstoreAction',
                'modelClass' => 'app\models\Site',
                //'group' => 'site',
                //'scenario' => 'kvstore',
                'viewName' => 'site-kvstore'
            ],
        ....
    ];
}

// Views
<?php $form = ActiveForm::begin(['id' => 'site-kvstore-form']); ?>
<?php echo $form->field($model, 'siteName'); ?>
<?php echo $form->field($model, 'siteDescription'); ?>
<?php ActiveForm::end(); ?>
```