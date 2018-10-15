<?php

namespace yiiplus\kvstore\models;

interface KvstoreInterface
{
    public function getKvstore($group, $key);
    public function setKvstore($group, $key, $value);
    public function deleteKvstore($group, $key);
    public function activateKvstore($group, $key);
    public function deactivateKvstore($group, $key);
}
