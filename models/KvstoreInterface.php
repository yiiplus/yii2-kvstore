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

/**
 * KvstoreInterface
 *
 * @author Hongbin Chen <hongbin.chen@aliyun.com>
 * @since 2.0.0
 */
interface KvstoreInterface
{
    public function getKvstore($group, $key);
    public function setKvstore($group, $key, $value);
    public function deleteKvstore($group, $key);
    public function activateKvstore($group, $key);
    public function deactivateKvstore($group, $key);
}
