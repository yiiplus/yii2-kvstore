yii2 kvstore
=============
在YiiPlus中提供了kvstore存储的方式来对数据量大的情况下进行优化。kvstore 是以 key->value 数据结构进行存储.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yiiplus/yii2-kvstore "^2.0.0"
```

or add

```
"yiiplus/yii2-kvstore": "^2.0.0"
```

to the require section of your `composer.json` file.

Subsequently, run

```php
./yii migrate --migrationPath=@yiiplus/kvstore/migrations
```

in order to create the kvstore table in your database.


Usage
-----

Add this to your main configuration's modules array

```php
'modules' => [
    'kvstore' => [
        'class' => 'yiiplus\kvstore\Module',
        'sourceLanguage' => 'en'
    ],
    ...
],
```

Add this to your main configuration's components array

```php
'components' => [
    'kvstore' => [
        'class' => 'yiiplus\kvstore\Kvstore'
    ],
    ...
]
```

Typical component usage

```php

$kvstore = Yii::$app->kvstore;

$value = $kvstore->get('section.key');

$value = $kvstore->get('key', 'section');

$kvstore->set('section.key', 'value');

$kvstore->set('section.key', 'value', null, 'string');

$kvstore->set('key', 'value', 'section', 'integer');

// Automatically called on set();
$kvstore->clearCache();

```

kvstore action
-----

To use a custom kvstore form, you can use the included `KvstoreAction`.

1. Create a model class with your validation rules.
2. Create an associated view with an `ActiveForm` containing all the kvstore you need.
3. Add `yiiplus\kvstore\actions\KvstoreAction` to the controller's actions.

The kvstore will be stored in section taken from the form name, with the key being the field name.

__Model__:

```php
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
```
__Views__:
```php
<?php $form = ActiveForm::begin(['id' => 'site-kvstore-form']); ?>
<?= $form->field($model, 'siteName') ?>
<?= $form->field($model, 'siteDescription') ?>
```
__Controller__:
```php
function actions(){
   return [
   		//....
            'site-kvstore' => [
                'class' => 'yiiplus\kvstore\KvstoreAction',
                'modelClass' => 'app\models\Site',
                //'scenario' => 'site',	// Change if you want to re-use the model for multiple kvstore form.
                'viewName' => 'site-kvstore'	// The form we need to render
            ],
        //....
    ];
}
```