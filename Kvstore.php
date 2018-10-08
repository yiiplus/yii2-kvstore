<?php

namespace yiiplus\kvstore;

use Yii;
use yii\base\Component;
use yii\caching\Cache;
use yii\helpers\Json;

class Kvstore extends Component
{
    /**
     * @var string kvstore model. Make sure your kvstore model calls clearCache in the afterSave callback
     */
    public $modelClass = 'yiiplus\kvstore\models\BaseKvstore';

    /**
     * Model to for storing and retrieving kvstore
     * @var \yiiplus\kvstore\models\KvstoreInterface
     */
    protected $model;

    /**
     * @var Cache|string the cache object or the application component ID of the cache object.
     * Kvstore will be cached through this cache object, if it is available.
     *
     * After the Kvstore object is created, if you want to change this property,
     * you should only assign it with a cache object.
     * Set this property to null if you do not want to cache the kvstore.
     */
    public $cache = 'cache';

    /**
     * @var Cache|string the front cache object or the application component ID of the front cache object.
     * Front cache will be cleared through this cache object, if it is available.
     *
     * After the Kvstore object is created, if you want to change this property,
     * you should only assign it with a cache object.
     * Set this property to null if you do not want to clear the front cache.
     */
    public $frontCache;

    /**
     * To be used by the cache component.
     *
     * @var string cache key
     */
    public $cacheKey = 'yiiplus/kvstore';

    /**
     * @var bool Whether to convert objects stored as JSON into an PHP array
     * @since 0.6
     */
    public $autoDecodeJson = false;

    /**
     * Holds a cached copy of the data for the current request
     *
     * @var mixed
     */
    private $_data = null;

    /**
     * Initialize the component
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->model = new $this->modelClass;

        if (is_string($this->cache)) {
            $this->cache = Yii::$app->get($this->cache, false);
        }
        if (is_string($this->frontCache)) {
            $this->frontCache = Yii::$app->get($this->frontCache, false);
        }
    }

    /**
     * Get's the value for the given key and section.
     * You can use dot notation to separate the section from the key:
     * $value = $kvstore->get('section.key');
     * and
     * $value = $kvstore->get('key', 'section');
     * are equivalent
     *
     * @param $key
     * @param string|null $section
     * @param string|null $default
     * @return mixed
     */
    public function get($key, $section = null, $default = null)
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

        $data = $this->getRawConfig();
        if (isset($data[$section][$key])) {
            $value = $data[$section][$key];
        } else {
            $value = $default;
        }
        return $value;
    }

    /**
     * Checks to see if a kvstore exists.
     * If $searchDisabled is set to true, calling this function will result in an additional query.
     * @param $key
     * @param string|null $section
     * @param boolean $searchDisabled
     * @return boolean
     */
    public function has($key, $section = null, $searchDisabled = false)
    {
        if ($searchDisabled) {
            $kvstore = $this->model->findKvstore($key, $section);
        } else {
            $kvstore = $this->get($key, $section);
        }
        return is_null($kvstore) ? false : true;
    }

    /**
     * @param $key
     * @param $value
     * @param null $section
     * @return boolean
     */
    public function set($key, $value, $section = null)
    {
        if (is_null($section)) {
            $pieces = explode('.', $key);
            $section = $pieces[0];
            $key = $pieces[1];
        }

        if ($this->model->setKvstore($section, $key, $value)) {
            return true;
        }
        return false;
    }

    /**
     * Returns the specified key or sets the key with the supplied (default) value
     *
     * @param $key
     * @param $value
     * @param null $section
     *
     * @return bool|mixed
     */
    public function getOrSet($key, $value, $section = null)
    {
        if ($this->has($key, $section, true)) {
            return $this->get($key, $section);
        } else {
            return $this->set($key, $value, $section);
        }
    }

    /**
     * Deletes a kvstore
     *
     * @param $key
     * @param null|string $section
     * @return bool
     */
    public function delete($key, $section = null)
    {
        if (is_null($section)) {
            $pieces = explode('.', $key);
            $section = $pieces[0];
            $key = $pieces[1];
        }
        return $this->model->deleteKvstore($section, $key);
    }

    /**
     * Deletes all kvstore. Be careful!
     *
     * @return bool
     */
    public function deleteAll()
    {
        return $this->model->deleteAllKvstore();
    }

    /**
     * Activates a kvstore
     *
     * @param $key
     * @param null|string $section
     * @return bool
     */
    public function activate($key, $section = null)
    {
        if (is_null($section)) {
            $pieces = explode('.', $key);
            $section = $pieces[0];
            $key = $pieces[1];
        }
        return $this->model->activateKvstore($section, $key);
    }

    /**
     * Deactivates a kvstore
     *
     * @param $key
     * @param null|string $section
     * @return bool
     */
    public function deactivate($key, $section = null)
    {
        if (is_null($section)) {
            $pieces = explode('.', $key);
            $section = $pieces[0];
            $key = $pieces[1];
        }
        return $this->model->deactivateKvstore($section, $key);
    }

    /**
     * Clears the kvstore cache on demand.
     * If you haven't configured cache this does nothing.
     *
     * @return boolean True if the cache key was deleted and false otherwise
     */
    public function clearCache()
    {
        $this->_data = null;
        if ($this->frontCache instanceof Cache) {
            $this->frontCache->delete($this->cacheKey);
        }
        if ($this->cache instanceof Cache) {
            return $this->cache->delete($this->cacheKey);
        }
        return true;
    }

    /**
     * Returns the raw configuration array
     *
     * @return array
     */
    public function getRawConfig()
    {
        if ($this->_data === null) {
            if ($this->cache instanceof Cache) {
                $data = $this->cache->get($this->cacheKey);
                if ($data === false) {
                    $data = $this->model->getKvstore();
                    $this->cache->set($this->cacheKey, $data);
                }
            } else {
                $data = $this->model->getKvstore();
            }
            $this->_data = $data;
        }
        return $this->_data;
    }
}
