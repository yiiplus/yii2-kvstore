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

namespace yiiplus\kvstore;

use Yii;
use yii\base\Component;
use yii\caching\Cache;
use yii\helpers\Json;

/**
 * 键值存储
 *
 * @category  PHP
 * @package   Yii2
 * @author    Hongbin Chen <hongbin.chen@aliyun.com>
 * @copyright 2006-2018 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-kvstore/licence.txt BSD Licence
 * @link      http://www.yiiplus.com
 */
class Kvstore extends Component
{
    /**
     * KV模型
     */
    protected $model;

    /**
     * 缓存对象的应用程序组件ID，如果您不想缓存kvstore请将此属性设置为null
     */
    public $cache = 'cache';

    /**
     * 缓存前缀
     */
    public $cachePrefix = 'yp_kvstore_';

    /**
     * 初始化
     */
    public function init()
    {
        parent::init();

        $this->model = new \yiiplus\kvstore\models\Kvstore;
        if (is_string($this->cache)) {
            $this->cache = Yii::$app->get($this->cache, false);
        }
    }

    /**
     * 获取
     *
     * @param string      $key
     * @param null|string $group
     * @param string|null $default
     * 
     * @return mixed
     */
    public function get($key, $group = null, $default = null)
    {
        list($group, $key) = $this->_groupKey($group, $key);
        if ($this->cache instanceof Cache) {
            $cacheKey = $this->cachePrefix . $group . '_' . $key;
            $value = $this->cache->get( $cacheKey );
            if ($value === false) {
                $value = $this->model->getKvstore($group, $key) ?? $default;
                if($value) {
                    $this->cache->set($cacheKey, $value);
                }
            }
        } else {
            $value = $this->model->getKvstore($group, $key) ?? $default;
        }

        return $value;
    }

    /**
     * 检查是否存在
     * 如果$searchDisabled设置为true，则调用此函数将直接查询数据库
     * 
     * @param string      $key
     * @param null|string $group
     * @param boolean     $searchDisabled
     * 
     * @return boolean
     */
    public function has($key, $group = null, $searchDisabled = false)
    {
        if ($searchDisabled) {
            list($group, $key) = $this->_groupKey($group, $key);
            $value = $this->model->getKvstore($group, $key);
        } else {
            $value = $this->get($key, $group);
        }
        return is_null($value) ? false : true;
    }

    /**
     * 设置
     * 
     * @param string      $key
     * @param string      $value
     * @param null|string $group
     * 
     * @return boolean
     */
    public function set($key, $value, $group = null)
    {
        list($group, $key) = $this->_groupKey($group, $key);
        if ($this->model->setKvstore($group, $key, $value)) {
            $this->_clearCache($group, $key);
            return true;
        }
        return false;
    }

    /**
     * 删除
     *
     * @param string      $key
     * @param null|string $group
     * 
     * @return bool
     */
    public function delete($key, $group = null)
    {
        list($group, $key) = $this->_groupKey($group, $key);
        $this->_clearCache($group, $key);
        return $this->model->deleteKvstore($group, $key);
    }

    /**
     * 激活
     *
     * @param string      $key
     * @param null|string $group
     * 
     * @return bool
     */
    public function activate($key, $group = null)
    {
        list($group, $key) = $this->_groupKey($group, $key);
        if( $this->model->activateKvstore($group, $key)) {
            $this->_clearCache($group, $key);
            return true;
        }
        return false;
    }

    /**
     * 取消激活
     *
     * @param string      $key
     * @param null|string $group
     * 
     * @return bool
     */
    public function deactivate($key, $group = null)
    {
        list($group, $key) = $this->_groupKey($group, $key);
        if( $this->model->deactivateKvstore($group, $key)) {
            $this->_clearCache($group, $key);
            return true;
        }
        return false;
    }

    /**
     * 清除缓存
     *
     * @param string $group
     * @param string $key
     * 
     * @return bool
     */
    private function _clearCache($group, $key)
    {
        if ($this->cache instanceof Cache) {
            $cacheKey = $this->cachePrefix . $group . '_' . $key;
            return $this->cache->delete($cacheKey);
        }
        return true;
    }

    /**
     * 参数处理
     * 
     * @param string $group
     * @param string $key
     * 
     * @return array
     */
    private function _groupKey($group, $key)
    {
        if (is_null($group)) {
            $pieces = explode('.', $key, 2);
            if (count($pieces) > 1) {
                $group = $pieces[0];
                $key = $pieces[1];
            } else {
                $group = '';
            }
        }
        return [$group, $key];
    }
}
