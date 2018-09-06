<?php

namespace yiiplus\kvstore\models;

interface KvstoreInterface
{

    /**
     * Gets a combined map of all the kvstore.
     * @return array
     */
    public function getKvstore();

    /**
     * Saves a kvstore
     *
     * @param $section
     * @param $key
     * @param $value
     * @param $type
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function setKvstore($section, $key, $value, $type);

    /**
     * Deletes a kvstore
     *
     * @param $key
     * @param $section
     * @return boolean True on success, false on error
     */
    public function deleteKvstore($section, $key);

    /**
     * Deletes all kvstore! Be careful!
     * @return boolean True on success, false on error
     */
    public function deleteAllKvstore();

    /**
     * Activates a kvstore
     *
     * @param $key
     * @param $section
     * @return boolean True on success, false on error
     */
    public function activateKvstore($section, $key);

    /**
     * Deactivates a kvstore
     *
     * @param $key
     * @param $section
     * @return boolean True on success, false on error
     */
    public function deactivateKvstore($section, $key);

    /**
     * Finds a single kvstore
     *
     * @param $key
     * @param $section
     * @return KvstoreInterface single kvstore
     */
    public function findKvstore($section, $key);
}
