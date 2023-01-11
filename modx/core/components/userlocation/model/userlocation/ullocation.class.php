<?php

class ulLocation extends xPDOObject
{

    public function __call($n, array $p)
    {
        echo __METHOD__.' says: '.$n;
    }

    public function isWork()
    {
        if (parent::get('active') AND parent::get('id') !== null) {
            return true;
        }

        return false;
    }

    public function toArray($keyPrefix = '', $rawValues = false, $excludeLazy = false, $includeRelated = false)
    {
        $array = parent::toArray($keyPrefix, $rawValues, $excludeLazy, $includeRelated);
        $array['confirmed'] = (isset($_SESSION['userlocation.id']) AND $_SESSION['userlocation.id'] === $array['id']) ? true : false;

        return $array;
    }

    public function get($k, $format = null, $formatTemplate = null)
    {
        if ($k === 'confirmed') {
            $v = (isset($_SESSION['userlocation.id']) AND $_SESSION['userlocation.id'] === parent::get('id')) ? true : false;
        } else {
            $v = parent::get($k, $format, $formatTemplate);
        }

        if ($k === 'properties') {
            $v = parent::get($k, $format, $formatTemplate);
            if (empty($v)) {
                $v = null;
            }
        }

        return $v;
    }

    public function set($k, $v = null, $vType = '')
    {
        if ($k === 'properties') {
            if (!empty($v) AND is_string($v)) {
                $v = json_decode($v, true);
            }
            if (empty($v) OR !is_array($v)) {
                $v = [];
            }
        }

        return parent::set($k, $v, $vType);
    }

    public function save($cacheFlag = null)
    {
        $this->beforeSave();

        $saved = parent:: save($cacheFlag);

        if ($saved) {
            $this->afterSave();
        }

        return $saved;
    }

    public function beforeSave()
    {

    }

    public function afterSave()
    {
        $properties = parent::get('properties');
        if (empty($properties)) {
            $c = $this->xpdo->newQuery('ulLocation');
            $c->command('UPDATE');
            $c->where([
                'id' => parent::get('id'),
            ]);
            $c->set([
                'properties' => null,
            ]);
            $c->prepare();
            if (!$c->stmt->execute()) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "[UserLocation] Could not save location properties\n".print_r($c->stmt->errorInfo(), true));
            }
        }

    }


}