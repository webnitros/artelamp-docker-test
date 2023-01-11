<?php

interface mspreMetaInterface
{
    /**
     * @return boolean
     */
    public function initialize();

    /**
     * Загрузка типов
     * @return array|null
     */
    public function loadTypes();

    /**
     * Вернет все поля
     * @return array|null
     */
    public function loadFields();


}

/**
 * The base class for Nsi.
 */
abstract class mspreMeta implements mspreMetaInterface
{
    /* @var modX $modx */
    /* @var mspre $mspre */
    public $modx;
    public $mspre;

    /* @var int $defaultWidth */
    protected $defaultWidth = 70;
    /* @var array|null $fields */
    protected $fields = null;
    /* @var array|null $types */
    protected $types = null;
    /* @var array|null $meta */
    protected $meta = null;
    /* @var string $prefix */
    protected $prefix = null;
    /* @var array|null|boolean */
    protected $captions = null;

    /* @var array|null $widths */
    protected $widths = null;

    /* @var array $errors */
    protected $errors = array();

    /**
     * @param mspre $mspre
     * @param array $config
     */
    function __construct(mspre &$mspre, array $config = array())
    {
        $this->mspre = $mspre;
        $this->modx =& $mspre->modx;
        $this->defaultWidth = $mspre->getOption('default_width');
    }

    /**
     * @return bool
     */
    public function initialize()
    {
        if (!$this->loadMeta()) {
            #$this->modx->log(modX::LOG_LEVEL_ERROR, "Не удалось загрузить поля с префиксом: " . $this->prefix, '', __METHOD__, __FILE__, __LINE__);
            return false;
        }
        return true;
    }

    /**
     * @param $value
     * @param $key
     * @param null $pr
     * @return string
     */
    public function addPrefixHeader($value, $key, $pr = null)
    {
        $prefix = $this->prefix;
        if ($this->prefix == 'text') {
            $prefix = '';
        }

        if ($pr == 'tooltip') {
            if (!empty($prefix)) {
                $prefix .= '-';
            }
            $value = $value . "({$prefix}$key)";
        }
        if ($this->prefix != 'text' and $pr == 'header') {
            $value = $value . "({$prefix})";
        }
        return $value;
    }

    /**
     * Вернет текст по ключу поля
     * @param $key
     * @return null|string
     */
    public function lexicon($key, $prAdd = null)
    {
        if ($prAdd == 'text') {
            $pr = 'header';
        } else {
            $pr = $prAdd;
        }

        $k = 'mspre_' . $pr . '_' . $key;
        $value = $this->modx->lexicon($k);
        if ($value == $k) {
            $k = 'ms2_product_' . $key;
            $value = $this->modx->lexicon($k);
            if ($value == $k) {
                $value = $key;
            }
        }
        return $this->addPrefixHeader($value, $key, $prAdd);
    }


    private function getSchema($field_name, $schema)
    {
        $validates = array('id', 'dataIndex', 'sortable', 'editor', 'actions');
        foreach ($validates as $k) {
            if (!isset($schema[$k])) {
                $this->addError($field_name, "Не найден ключ {$k} для поля {$field_name} не найден");
                return false;
            }
        }

        if ($schema['editor']) {
            if (!isset($schema['editor']['xtype'])) {
                $this->addError($field_name, "Не найден ключ editor->xtype для поля {$field_name} не найден");
                return false;
            }
            if (!isset($schema['editor']['name'])) {
                $this->addError($field_name, "Не найден ключ editor->name для поля {$field_name} не найден");
                return false;
            }
        }


        foreach ($validates as $k) {
            if (!isset($schema[$k])) {
                $this->addError($k, "Не найден ключ {$k} для поля {$field_name} не найден");
                return false;
            }
        }

        $placeholders = array(
            'name' => $field_name,
        );

        $pl = $this->mspre->makePlaceholders($placeholders, '', '{', '}', false);

        // Замена имени
        foreach ($schema as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if (strripos($v, '{') !== false) {
                        $schema[$key][$k] = str_replace($pl['pl'], $pl['vl'], $v);
                    }
                }
            } else {
                if (strripos($value, '{') !== false) {
                    $schema[$key] = str_replace($pl['pl'], $pl['vl'], $value);
                }
            }
        }


        $field_key = $this->getPrefix($field_name, true);


        $schema['type_field'] = $this->getType($field_name);
        $schema['text'] = $this->lexicon($field_key, 'text');
        $schema['header'] = $this->lexicon($field_key, 'header');
        $schema['tooltip'] = $this->lexicon($field_key, 'tooltip');
        $schema['width'] = $this->getColumnWidth($field_name);


        $this->meta[$field_name] = $schema;
        return true;
    }


    /**
     * @param string $field_name
     * @return int
     */
    public function getColumnWidth($field_name)
    {
        $width = $this->defaultWidth;
        if ($this->mspre->controller) {
            if (is_null($this->widths)) {
                $fields = $this->mspre->getFieldsTable($this->mspre->controller, 'table', false, null);
                foreach ($fields as $value) {
                    $field = $this->getPrefix($value['field']);
                    $this->widths[$field] = $value['size'];
                }
            }
            if (isset($this->widths[$field_name])) {
                $width = $this->widths[$field_name];
            }
        }

        $width = (int)$width;
        return $width == 0 ? $this->defaultWidth : $width;
    }


    /**
     * @param $field
     * @param $msg
     */
    public function addError($field, $msg)
    {
        $this->errors[$field] = $msg;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return int
     */
    public function isError()
    {
        return count($this->errors);
    }

    /**
     * Вернет все поля
     * @return array|null|false
     */
    public function loadMeta()
    {
        if (is_null($this->meta)) {
            if ($fields = $this->loadFields()) {

                if (!$types = $this->loadTypes()) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, "Не удалось загрузить типы полей", '', __METHOD__, __FILE__, __LINE__);
                    return false;
                }

                foreach ($fields as $field => $type) {
                    if (isset($types[$type])) {
                        $schema = $types[$type];
                        $response = $this->getSchema($field, $schema);
                        if ($response === false) {
                            continue;
                        }
                    } else {
                        $this->addError($field, "Тип данных {$type} для поля {$field} не найден");
                    }
                }


            }
        }

        if ($this->isError()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Error load map {$this->prefix} fields" . print_r($this->getErrors(), 1), '', __METHOD__, __FILE__, __LINE__);
        }

        return $this->meta;
    }


    /**
     * @param $key
     * @return array|boolean
     */
    private function meta($key)
    {
        $key = $this->getPrefix($key);
        if (!isset($this->meta[$key])) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Error field name not specified", '', __METHOD__, __FILE__, __LINE__);
            return false;
        }
        return $this->meta[$key];
    }


    /**
     * Вернет все мета данные класса
     * @return array|null
     */
    public function getMetaData($excludePrefix = false)
    {
        $meta = $this->meta;
        if ($excludePrefix) {
            $newMeta = array();
            foreach ($meta as $field => $item) {
                $key = $this->getPrefix($field, true);
                $newMeta[$key] = $item;
            }
        } else {
            $newMeta = $meta;
        }
        return $newMeta;
    }


    /**
     * @param $field
     * @return bool|mixed
     */
    public function getType($field)
    {
        return isset($this->fields[$field]) ? $this->fields[$field] : false;
    }

    /**
     * Вернет все мета данные класса
     * @return array
     */
    public function getFields()
    {
        return array_keys($this->meta);
    }


    /**
     * @param string $field_name
     * @param string|null $key
     * @return array|boolean
     */
    public function getMeta($field_name, $key = null)
    {
        if (empty($field_name)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Error field name not specified {$field_name}", '', __METHOD__, __FILE__, __LINE__);
            return false;
        }
        if (!$meta = $this->meta($field_name)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Error get meta field {$field_name}", '', __METHOD__, __FILE__, __LINE__);
            return false;
        }

        if ($key) {
            if (!isset($meta[$key])) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "specified key {$key} for field {$field_name} not found ", '', __METHOD__, __FILE__, __LINE__);
                return false;
            }
            return $meta[$key];
        }
        return $meta;
    }

    /**
     * Вернет поле с префиксом
     * @param $key
     * @param boolean $back true вернет наименование поля без префикса
     * @return string
     */
    public function getPrefix($key, $back = false)
    {
        if (empty($key)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Error field name not specified", '', __METHOD__, __FILE__, __LINE__);
            return false;
        }
        $prefix = $this->prefix . '-';

        if ($back) {
            if (strripos($key, $prefix) !== false) {
                return str_ireplace($prefix, '', $key);
            } else {
                return $key;
            }
        } else {
            if (strripos($key, $prefix) === false) {
                return $prefix . $key;
            }
        }
        return $key;
    }


    /**
     * Вернет текст по ключу поля
     * @return null|array|boolean
     */
    public function loadCaption()
    {
        if (is_null($this->captions)) {
            $this->captions = false;
        }
        return $this->captions;
    }


    /**
     * Вернет текст по ключу поля
     * @param $key
     * @return boolean|string
     */
    public function getCaption($key)
    {
        if ($collection = $this->loadCaption()) {
            if (isset($collection[$key])) {
                return $collection[$key];
            }
        }
        return $key;
    }

}