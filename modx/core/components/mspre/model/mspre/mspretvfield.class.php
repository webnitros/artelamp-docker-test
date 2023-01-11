<?php

class mspreTvField extends xPDOSimpleObject
{
    /** @var modTemplateVar|null $Tv */
    protected $Tv = null;

    protected $_originalFieldMeta;
    protected $values = array();


    /**
     * mspreTvField constructor
     *
     * @param xPDO $xpdo
     */
    function __construct(xPDO & $xpdo)
    {
        parent::__construct($xpdo);
        $this->_originalFieldMeta = $this->_fieldMeta;
    }


    /**
     * Получение значение тв параметра из поля
     * @param msProduct|modResource $resource
     * @return array|string|false
     * */
    public function TemplateVars($resource)
    {
        $current = false;
        $separators = $this->separator();
        $TemplateVavs = $resource->getTemplateVars();

        foreach ($TemplateVavs as $tv) {
            /* @var modTemplateVar $tv */
            if ($tv->get('name') == $this->get('name')) {
                $current = $tv->get('value');
            }
        }
        if (!empty($separators) and $current) {
            $current = explode($separators, $current);
        }
        return $current;
    }

    /**
     * Procedure for replacing the old value with a new one
     *
     * @param msProduct|modResource $resource object product
     * @param string $replaceable replaceable value
     * @param string $new_value replaceable value
     * @return boolean Whether or not the TV has access to the specified Template
     */
    public function newValue($resource, $replaceable, $new_value)
    {
        if (!is_object($resource)) {
            $this->xpdo->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": error not object product");
            return false;
        } else if (!is_string($replaceable)) {
            $this->xpdo->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": error not string replaceable");
            return false;
        } else if (!is_string($new_value)) {
            $this->xpdo->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": error not string replaceable");
            return false;
        }

        // В случае когда есть разделитель
        $current = $this->TemplateVars($resource);
        if (empty($current)) {
            return false;
        }

        if (is_array($current)) {
            $new = array();
            if (count($current) > 0) {
                foreach ($current as $k => $v) {
                    if ($replaceable == $v) {
                        $v = $new_value;
                    }
                    $new[$v] = $v;
                }
                sort($new);
            }
            $new = implode($this->separator(), $new);
        } else {
            $new = $new_value;
        }
        return $new;
    }

    /**
     * Добавление нового значения
     * @param modResource|msProduct $resource
     * @param $new_value
     * @return array|bool|string
     */
    public function addValue($resource, $new_value, $updategrig = false)
    {
        $current = $this->TemplateVars($resource);
        #if (empty($current)) {
        #    return false;
        #}


        // Разрешает добавлять несколько значений
        $add = $this->get('isadd');
        if ($updategrig) {
            $new = $new_value;
        } else {
            if (is_array($current) and $add) {
                $new = array();
                $new[] = $new_value;
                if (count($current) > 0) {
                    foreach ($current as $k => $v) {
                        $new[$v] = $v;
                    }
                    $new = array_unique($new);
                    $new = array_filter($new);
                    sort($new);
                }
                $new = implode($this->separator(), $new);
            } else {
                $new = $new_value;
            }
        }
        return $new;
    }


    /**
     * Удаление значения нового значения
     * @param msProduct|modResource $resource
     * @param string $remove_value
     * @return bool|string
     */
    public function removeValue($resource, $remove_value)
    {
        $current = $this->TemplateVars($resource);
        if (is_array($current)) {
            $new = array();
            if (count($current) > 0) {
                foreach ($current as $k => $v) {
                    if ($v != $remove_value) {
                        $new[$v] = $v;
                    }
                }
                $new = array_unique($new);
                $new = array_filter($new);
                sort($new);
            }
            $new = implode($this->separator(), $new);
        } else {
            $new = '';
        }
        return $new;
    }

    /**
     * Check to see if the TV has access to a Template
     *
     * @param mixed $templatePk Either the ID, name or object of the Template
     * @return boolean Whether or not the TV has access to the specified Template
     */
    public function hasTemplate($templatePk)
    {
        if (!$object = $this->xpdo->getObject('modTemplate', $templatePk)) {
            return false;
        }
        return $this->loadData()->hasTemplate($object->get('templatename'));
    }


    /**
     * @param $id
     * @param $template_id
     * @return array|null|string
     */
    public function valueEntered($id, $template_id)
    {
        $values = null;
        if ($this->hasTemplate($template_id)) {
            $this->possibleValues();
            $values = $this->enteredValues(array($id));
        }
        return $values;
    }


    /**
     * Get possible values TV
     *
     * @return array|string
     */
    public function possibleValues()
    {
        $data = array();

        $default_text = $this->get('elements');
        
        if (strripos($default_text, '@EVAL') !== false) {
            global $modx;
            $value = str_ireplace('@EVAL ', '', $default_text);
            $default_text = eval($value);
        }

        
        if (!empty($default_text)) {
            $separator = $this->separator();
            $ext = !empty($separator) ? explode($this->separator(), $default_text) : $default_text;
            if (is_array($ext) and count($ext) > 0) {
                foreach ($ext as $ex) {
                    $arr = explode('==', $ex);
                    if (count($arr) > 1) {
                        $value = isset($arr[0]) ? $arr[0] : '';
                        $key = isset($arr[1]) ? $arr[1] : '';
                    } else {
                        $value = $key = $arr[0];
                    }
                    $data[] = $this->separatorBetween($key, $value);
                }
            }
        }
        $this->values = $data;
        return $data;
    }


    /**
     * get the values for the specified goods
     *
     * @param  array $ids product id
     *
     * @return array|bool
     */
    public function getValuesEntered($ids)
    {
        $data = false;

        if (empty($ids) or !is_array($ids)) {
            $this->xpdo->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": not ids ");
        }

        $foundValues = array();

        /* @var modTemplateVarResource $object */
        $q = $this->xpdo->newQuery('modTemplateVarResource');
        $q->select('value,contentid');
        $q->where(array(
            'tmplvarid' => $this->loadData()->get('id'),
            'contentid:IN' => $ids
        ));
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $foundValues[] = $row['contentid'];
                $data[] = $row['value'];
            }
        }

        /* @var mspre $mspre */
        $mspre = $this->xpdo->getService('mspre', 'mspre', MODX_CORE_PATH . 'components/mspre/model/');
        $defaultValues = $mspre->getIntersect($ids, $foundValues);
        if (count($defaultValues) > 0) {
            // TODO В случае когда запись не создана и есть значения по умолчанию то эти значения автоматически устанавливаются для записи так как на фронтенде будут отображатся именно эти значения
            $default_text = $this->get('default_text');
            if (!empty($default_text)) {
                $data = explode($this->separator(), $default_text);
            }
        }

        return $data;
    }

    /**
     * get the values for the specified goods
     *
     * @param  array $ids
     * @return array|string
     */
    public function enteredValues($ids)
    {
        $data = array();


        $separator = $this->separator();
        if ($values_products = $this->getValuesEntered($ids)) {
            $values_products = array_unique($values_products);
            if (count($values_products) > 0) {
                switch ($this->get('type')) {
                    case 'option':
                    case 'checkbox':
                    case 'listbox':
                    case 'listbox-multiple':
                    case 'list-multiple-legacy':
                        foreach ($values_products as $val) {
                            if ($separator) {
                                $enteredvalue = explode($separator, $val);
                            } else {
                                $enteredvalue = array($val);
                            }
                            if (count($enteredvalue) > 0) {
                                foreach ($enteredvalue as $k) {
                                    if ($val = $this->getValueTv($k)) {
                                        $data[$val['id']] = $val;
                                    }
                                }
                            }
                        }
                        break;
                    case 'autotag':
                        foreach ($values_products as $val) {
                            if ($separator) {
                                $enteredvalue = explode($separator, $val);
                            } else {
                                $enteredvalue = array($val);
                            }


                            if (count($enteredvalue) > 0) {
                                foreach ($enteredvalue as $k) {
                                    $data[$k] = array(
                                        'id' => $k,
                                        'name' => $k
                                    );
                                }
                            }
                        }

                        break;
                    case 'resourcelist':
                        if (count($values_products) > 0) {
                            $q = $this->xpdo->newQuery('modResource');
                            $q->select('id,pagetitle as name');
                            $q->where(array(
                                'id:IN' => $values_products,
                            ));
                            if ($q->prepare() && $q->stmt->execute()) {
                                $data = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
                            }
                        }
                        break;
                    default:
                        if (count($values_products) > 0) {
                            foreach ($values_products as $k) {
                                $data[$k] = array(
                                    'id' => $k,
                                    'name' => $k
                                );
                            }
                        }
                        break;
                }
            }
        }
        sort($data);
        return $data;
    }

    /**
     * Get value possible and set key
     */
    public function getValueTv($key)
    {
        if (!is_string($key)) {
            $this->xpdo->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": not string " . print_r($key, 1));
            return false;
        }
        if (!empty($key)) {
            $values = $this->possibleValues();
            foreach ($values as $default) {
                if ($default['id'] == $key) {
                    return $default;
                }
            }
        }
        return false;
    }


    /**
     * Separator between key and value
     *
     * @return array|string
     */
    private function separatorBetween($key, $value)
    {
        return array(
            'id' => $key,
            'name' => $value,
        );
    }


    /**
     * Check to see if the TV has access to a Template
     *
     * @param mixed $templatePk Either the ID, name or object of the Template
     * @return boolean Whether or not the TV has access to the specified Template
     */
    public function defaul($templatePk)
    {
        return $this->loadData()->hasTemplate($templatePk);
    }

    /**
     * get separator
     *
     * @return boolean|string
     */
    public function separator()
    {
        $separator = $this->get('separator');
        return $separator ? $separator : false;
    }


    /**
     * @param  modTemplateVar|null|object|xPDOObject
     */
    public function setTvObject($object)
    {
        if (is_null($this->Tv)) {
            if ($object instanceof modTemplateVar) {
                $this->Tv = $object;
            }
        }

    }

    /**
     * @param $value
     * @param int $resourceId
     * @return mixed
     */
    public function prepareOutput($value, $resourceId)
    {
        if (!empty($value) and $tv = $this->loadData()) {
            switch ($tv->get('type')) {
                case 'image':
                case 'file':
                    $value = $this->loadData()->prepareOutput($value, $resourceId);
                    break;
                default:
                    break;
            }
        }
        return $value;
    }

    /**
     * @return modTemplateVar|null|object|xPDOObject
     */
    public function loadData()
    {
        if (!is_object($this->Tv) || !($this->Tv instanceof modTemplateVar)) {
            if (!$this->Tv = $this->getOne('Tv')) {
                $this->Tv = $this->xpdo->newObject('modTemplateVar');
            }
        }
        return $this->Tv;
    }


    /**
     * @param array|string $k
     * @param null $format
     * @param null $formatTemplate
     *
     * @return array|mixed|null|xPDOObject
     */
    public function get($k, $format = null, $formatTemplate = null)
    {

        if (is_array($k)) {
            $array = array();
            foreach ($k as $v) {
                $array[$v] = isset($this->_originalFieldMeta[$v])
                    ? parent::get($v, $format, $formatTemplate)
                    : $this->get($v, $format, $formatTemplate);
            }

            return $array;
        } elseif (isset($this->_originalFieldMeta[$k])) {
            return parent::get($k, $format, $formatTemplate);
        } else if (isset($this->loadData()->_fields[$k])) {
            return $this->loadData()->get($k, $format, $formatTemplate);
        } else {
            return parent::get($k, $format, $formatTemplate);
        }
    }

    /**
     * @param string $keyPrefix
     * @param bool $rawValues
     * @param bool $excludeLazy
     * @param bool $includeRelated
     *
     * @return array
     */
    public function toArray($keyPrefix = '', $rawValues = false, $excludeLazy = false, $includeRelated = false)
    {
        $original = parent::toArray($keyPrefix, $rawValues, $excludeLazy, $includeRelated);
        $additional = $this->loadData()->toArray($keyPrefix, $rawValues, $excludeLazy, $includeRelated);
        $intersect = array_keys(array_intersect_key($original, $additional));
        foreach ($intersect as $key) {
            unset($additional[$key]);
        }
        return array_merge($original, $additional);
    }

}