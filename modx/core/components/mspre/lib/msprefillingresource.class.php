<?php


class msPreFillingResource
{
    /* @var modX $modx */
    public $modx;

    /* @var mspre $mspre */
    public $mspre;

    /* @var array|null $tv_values */
    private $tv_values = null;

    /* @var modTemplateVar[] $tv_type */
    private $tv_type = null;

    /* @var array|null $tv_type_field */
    private $tv_type_field = null;

    /* @var boolean $tvEnable */
    private $tvEnable = false;


    /* @var array|null $fields */
    private $fields = null;


    protected $class_key = null;
    protected $controller = null;
    protected $fieldsTypePhp = null;


    /**
     * @param array $config
     */
    function __construct(mspre $mspre, array $config = [])
    {
        $this->modx = &$mspre->modx;
        $this->mspre = &$mspre;
        $this->config = array_merge(array(
            'memoryCacheSize' => '64MB',
            'path_save' => MODX_CORE_PATH . 'export/',
            'path_tmp' => MODX_CORE_PATH . 'export/',
            'character_separate' => $this->mspre->getOption('character_separate_options', null, '||'),
            'export_date_format' => $this->mspre->getOption('export_date_format', null, 'Y-m-d H:i:s'),
            'export_price_format' => $this->mspre->getOption('export_price_format', null, '[3, ",", " "]'),
            'export_weight_format' => $this->mspre->getOption('export_weight_format', null, '[2, ",", " "]'),
            'add_url' => array_map('trim', array_filter(explode(',', $this->mspre->getOption('export_add_url', null, '')))),
            'export_values_default_empty' => array_map('trim', array_filter(explode(',', $this->mspre->getOption('export_values_default_empty', null, '')))),
            'export_add_default_columns' => $this->mspre->getOption('export_add_default_columns', null, '')
        ), $config);

    }


    /**
     * @return array
     */
    public function getDefaultEmptyColumns()
    {
        $fields = array();
        $addDefaultFields = $this->config['export_add_default_columns'];
        if (!empty($addDefaultFields)) {
            $addDefaultFields = explode(',', $addDefaultFields);
            foreach ($addDefaultFields as $addDefaultField) {
                list($field, $value) = explode(':', $addDefaultField);
                $fields[$field] = $value;
            }
        }
        return $fields;
    }


    /**
     * @param $controller
     * @param array $ids
     * @param string $context_key
     * @return bool|array
     */
    public function process($controller, $ids = array(), $context_key = 'web')
    {

        $this->controller = $controller;
        $this->class_key = $controller == 'product' ? 'msProduct' : 'modResource';


        $fields = $this->getFields();
        $aliasesFields = $this->getAliases();
        $defaultColumns = $this->getDefaultEmptyColumns();


        $resources = $this->getResources($ids);
        $this->getTvValues($ids);
        $this->getOptionsValues($ids);


        $previewUrlMake = false;
        $previewUrl = false;
        if (in_array('preview_url', $fields)) {
            $previewUrl = true;
            if ($this->modx->getOption('cache_alias_map') && isset($this->modx->aliasMap)) {
                $previewUrlMake = true;
            }
        }


        // Искать категорию
        $categories = null;
        if (in_array('category_name', $fields)) {
            $categories = $this->getCategories();
        }

        $vendors = null;
        if (in_array('vendor_name', $fields)) {
            $vendors = $this->getVendors();
        }


        if (!$Execel = $this->mspre->loadExecel()) {
            return false;
        }


        $separate = $this->modx->getOption('mspre_character_separate_options');
        $site_url = $this->modx->getOption('site_url');

        // Перебор массива для экспорта
        $data = array();
        foreach ($resources as $resource) {

            $prepare = array();

            $resourceID = $resource['id'];
            $product = array();


            // Перебор полей
            foreach ($fields as $field) {

                $value = '';
                if (array_key_exists($field, $defaultColumns)) {
                    $resource[$field] = $defaultColumns[$field];
                }


                /* if ($field == 'options-autocomplite') {
                     $key = prefixOptions($field);
                     echo '<pre>';
                     print_r($key);
                     print_r($resource);
                     die;
                 }*/


                if ($key = prefixOptions($field)) {


                    #$value = implode($separate, $value);
                    $value = '';
                    if (array_key_exists($key, $resource)) {
                        $values = $resource[$key];
                        $value = implode($separate, $values);
                    } else if ($this->optionEnable) {
                        $value = $this->getOptionValue($resourceID, $key);
                        $value = implode($separate, $value);


                    }

                    #  echo '<pre>';
                    # print_r($value);
                    # die;

                    #$values = $resource[$key];
                    #$value = implode(',', $values);
                } else if ($this->tvEnable and $key = prefixTv($field)) {
                    $value = $this->getTvValue($resourceID, $key);
                } else {
                    switch ($field) {
                        case 'preview_url':
                            if ($previewUrl) {
                                if ($previewUrlMake) {
                                    $value = $this->modx->makeUrl($resourceID, $context_key);
                                } else {
                                    $value = $this->modx->getOption('site_url') . $resource['uri'];
                                }
                            }
                            break;
                        default:
                            $value = $resource[$field];
                            break;
                    }
                }
                $product[$field] = $value;
            }


            if ($vendors and array_key_exists($resource['vendor'], $vendors)) {
                $product['vendor_name'] = $vendors[$resource['vendor']]['vendor.name'];
            }


            if ($categories and array_key_exists($resource['parent'], $categories)) {
                $product['category_name'] = $categories[$resource['parent']];
            }


            foreach ($product as $f => $value) {
                if ($type = $this->getFieldsType($f)) {
                    $phptype = $type['phptype'];
                    $default = $type['default'];
                    $type_field = $type['type'];
                    switch ($phptype) {
                        case 'boolean':
                            $value = !empty($value) ? $value : $default;
                            break;
                        case 'integer':
                            $value = !empty($value) ? $value : $default;
                            break;
                        case 'float':
                            if ($type_field == 'price') {
                                $value = (!empty($value) and $value != '0') ? $this->formatPrice($value) : $default;
                            } else {
                                $value = (!empty($value) and $value != '0') ? $this->formatWeight($value) : $default;
                            }
                            break;
                        #character_separate_float
                        case 'timestamp':
                            if ($value == 0 or strripos($value, '1970-01-01') !== false) {
                                $value = '';
                            } else {
                                $value = !empty($this->config['export_date_format']) ? strval(date($this->config['export_date_format'], $value)) : $value;
                            }
                            break;
                        case 'json':
                            $value = $this->getJSONField($f, $value);
                            break;
                        default:
                            break;
                    }
                }

                if (in_array($f, $this->config['add_url'])) {
                    $value = !empty($value) ? rtrim($site_url, '/') . '/' . ltrim($value, '/') : '';
                }

                if (array_key_exists($f, $aliasesFields)) {
                    $f = $aliasesFields[$f];
                }


                $prepare[$f] = $value;
            }

            /* Событие для подготовки данных к выгрузке */
            $response = $this->mspre->invokeEvent('msPreExportToArrayAfter', array(
                'controller' => $controller,
                'resource' => $resource,
                'prepare' => $prepare,
            ));
            if (!$response['success']) {
                return $response['message'];
            }
            $data[] = $response['data']['prepare'];
        }

        return $data;
    }

    /**
     * Function for weight format
     *
     * @param $weight
     *
     * @return int|mixed|string
     */
    function formatPrice($weight = 0)
    {
        $format = $this->config['export_price_format'];
        if (!$wf = json_decode($format)) {
            $wf = array(2, ',', ' ');
        }
        $weight = number_format($weight, $wf[0], $wf[1], $wf[2]);
        if (true) {
            $tmp = explode($wf[1], $weight);
            $tmp[1] = rtrim(rtrim(@$tmp[1], '0'), '.');
            $weight = !empty($tmp[1])
                ? $tmp[0] . $wf[1] . $tmp[1]
                : $tmp[0];
        }

        return $weight;
    }

    /**
     * Function for weight format
     *
     * @param $weight
     *
     * @return int|mixed|string
     */
    function formatWeight($weight = 0)
    {
        $format = $this->config['export_weight_format'];
        if (!$wf = json_decode($format)) {
            $wf = array(3, ',', ' ');
        }
        $weight = number_format($weight, $wf[0], $wf[1], $wf[2]);
        if (true) {
            $tmp = explode($wf[1], $weight);
            $tmp[1] = rtrim(rtrim(@$tmp[1], '0'), '.');
            $weight = !empty($tmp[1])
                ? $tmp[0] . $wf[1] . $tmp[1]
                : $tmp[0];
        }

        return $weight;
    }


    /**
     * @param $field
     * @param $value
     * @return string
     */
    function getJSONField($field, $value)
    {
        #if ($key = prefixOptions($field)) {
        if (!empty($value)) {
            $data = is_array($value) ? $value : $this->modx->fromJSON($value);
            if (!empty($data)) {
                $value = implode($this->config['character_separate'], $data);
            }
        }
        #}
        if (strripos($value, '[]') !== false) {
            $value = '';
        }
        return $value;
    }


    public function getIds()
    {

        $data = null;
        // Получаем все ID экспортируемых товаров
        $ids = $cacheManager = $this->mspre->getCacheManager();
        if (empty($ids)) {
            exit('Передано 0 товаров для экспорта');
        }

    }


    /**
     * Вернет список полей для экспорта
     * @return array|null
     */
    public function getFields()
    {
        if (is_null($this->fields)) {
            $fields = $this->mspre->getFieldsTable($this->controller, 'export');
            $fields = array_column($fields, 'field');

            /* Событие для подготовки данных к выгрузке */
            $response = $this->mspre->invokeEvent('msPreExportGetFields', array(
                'fields' => $fields,
            ));
            if (!$response['success']) {
                $this->fields = false;
                return $response['message'];
            } else {
                $this->fields = $response['data']['fields'];
            }

        }
        return $this->fields;
    }


    /**
     * Вернет список альясов для полей экспорта
     */
    public function getAliases()
    {
        $aliasesFields = array();
        // Алиасы для полей
        $alias_export = $this->mspre->getOption('mspre_alias_field_export', null, '');
        $alias_export = explode(',', $alias_export);
        $alias_export = array_map('trim', $alias_export);
        foreach ($alias_export as $item) {
            list($field, $alias) = explode(':', $item);
            $aliasesFields[$field] = $alias;
        }

        return $aliasesFields;
    }


    public function getMetaResource()
    {
        $meta = $this->modx->getFieldMeta($this->class_key);
        $meta = array_merge($meta, $this->modx->getFieldAliases($this->class_key));

        if ($this->class_key == 'msProduct') {
            $meta = array_merge($meta, $this->modx->getFieldMeta('msProductData'));
            $meta = array_merge($meta, $this->modx->getFieldAliases('msProductData'));
        }
        return $meta;
    }


    /**
     * @return null
     */
    public function getCategories()
    {
        $categories = null;
        if ($this->class_key == 'msProduct') {
            $q = $this->modx->newQuery('msCategory');
            $q->select('id,pagetitle as name');
            $q->where(array(
                'class_key' => 'msCategory',
            ));
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $categories[$row['id']] = $row['name'];
                }
            }
        } else {
            $q = $this->modx->newQuery('modResource');
            $q->select('id,pagetitle as name');
            $q->where(array(
                'isfolder' => 1,
            ));
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $categories[$row['id']] = $row['name'];
                }
            }
        }
        return $categories;
    }


    /**
     * @return null
     */
    public function getVendors()
    {
        $vendors = null;
        if ($this->class_key == 'msProduct') {
            $q = $this->modx->newQuery('msVendor');
            $q->select($this->modx->getSelectColumns('msVendor', 'msVendor', 'vendor.'));
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $vendors[$row['vendor.id']] = $row;
                }
            }
        }
        return $vendors;
    }


    /**
     * @param $field
     * @return bool
     */
    public function getFieldsType($f)
    {
        $value = '';
        if (is_null($this->fieldsTypePhp)) {
            $this->fieldsTypePhp = array();

            $defaultValues = array();
            $emptyDefault = $this->config['export_values_default_empty'];
            if (!empty($emptyDefault)) {
                foreach ($emptyDefault as $item) {
                    list($field, $value) = explode(':', $item);
                    $defaultValues[$field] = $value;
                }
            }


            $meta = $this->getMetaResource();
            foreach ($meta as $field => $data) {
                if (!is_array($data)) {
                    // Получение мета данных для alias
                    if (array_key_exists($data, $meta)) {
                        $data = $meta[$data];
                    }
                }
                $phptype = $data['phptype'];
                $default = '';
                switch ($phptype) {
                    case 'boolean':
                    case 'integer':
                    case 'float':
                    case 'timestamp':
                    case 'json':
                        if (!array_key_exists($field, $this->fieldsTypePhp)) {
                            $type = '';
                            if ('float' == $phptype) {
                                $type = $data['precision'] == '12,2' ? 'price' : 'weight';
                            }

                            if (array_key_exists($field, $defaultValues)) {
                                $default = $defaultValues[$field];
                            }

                            $this->fieldsTypePhp[$field] = array(
                                'phptype' => $phptype,
                                'default' => $default,
                                'type' => $type,
                            );
                        }
                        break;
                    default:
                        break;
                }
            }
        }
        if (array_key_exists($f, $this->fieldsTypePhp)) {
            $value = $this->fieldsTypePhp[$f];
        }
        return $value;
    }


    /**
     * Получаем ТВ параметры для экспорта
     * @param $ids
     * @return array|bool
     */
    public function getOptionsValues($ids)
    {
        $fieldsOptions = null;
        $fields = $this->getFields();
        foreach ($fields as $field) {
            if ($key = prefixOptions($field)) {
                $fieldsOptions[] = $key;
            }
        }

        if (!$fieldsOptions) {
            return false;
        }

        /* @var msOption $object */
        $qTvType = $this->modx->newQuery('msOption');
        $qTvType->where(array(
            'key:IN' => $fieldsOptions,
        ));
        if ($objectList = $this->modx->getCollection('msOption', $qTvType)) {
            foreach ($objectList as $object) {
                $id = $object->get('id');
                $name = $object->get('key');
                $this->option_type_field[$name] = $id;
                $this->option_type[$id] = $object;
            }
        }

        /* @var modTemplateVarResource $tv */
        $qTv = $this->modx->newQuery('msProductOption');
        $qTv->select('product_id,key,value');
        $qTv->where(array(
            'key:IN' => array_keys($this->option_type_field),
            'product_id:IN' => $ids,
        ));
        if ($qTv->prepare() && $qTv->stmt->execute()) {
            while ($row = $qTv->stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->option_values[$row['product_id']][$row['key']][] = $row['value'];
            }
        }

        if (!empty($this->option_values)) {
            $this->optionEnable = true;
        }
        return true;
    }


    /* @var array|null $option_values */
    private $option_values = null;

    /* @var modTemplateVar[] $option_type */
    private $option_type = null;

    /* @var array|null $option_type_field */
    private $option_type_field = null;

    /* @var boolean $optionEnable */
    private $optionEnable = false;


    /**
     * Получаем ТВ параметры для экспорта
     * @param $ids
     * @return array|bool
     */
    public function getTvValues($ids)
    {
        $fieldsTV = null;
        $fields = $this->getFields();
        foreach ($fields as $field) {
            if ($key = prefixTv($field)) {
                $fieldsTV[] = $key;
            }
        }

        if (!$fieldsTV) {
            return false;
        }


        /* @var modTemplateVar $object */
        $qTvType = $this->modx->newQuery('modTemplateVar');
        $qTvType->where(array(
            'name:IN' => $fieldsTV,
        ));
        if ($objectList = $this->modx->getCollection('modTemplateVar', $qTvType)) {
            foreach ($objectList as $object) {
                $id = $object->get('id');
                $name = $object->get('name');
                $this->tv_type_field[$name] = $id;
                $this->tv_type[$id] = $object;
            }
        }


        /* @var modTemplateVarResource $tv */
        $qTv = $this->modx->newQuery('modTemplateVarResource');
        $qTv->select('contentid,tmplvarid,value');
        $qTv->where(array(
            'tmplvarid:IN' => $this->tv_type_field,
            'contentid:IN' => $ids,
        ));
        if ($qTv->prepare() && $qTv->stmt->execute()) {
            while ($row = $qTv->stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->tv_values[$row['contentid']][$row['tmplvarid']] = $row['value'];
            }
        }
        if (!empty($this->tv_values)) {
            $this->tvEnable = true;
        }

        return true;
    }


    /**
     * Вернет отреднговое знание из ТВ параметра
     * @param $resource_id
     * @param $field
     * @return mixed|string
     */
    public function getOptionValue($resource_id, $field)
    {
        $value = [];
        if (array_key_exists($resource_id, $this->option_values)) {
            #$option_id = $this->option_type_field[$field];
            $values = $this->option_values[$resource_id];
            if (array_key_exists($field, $values) and !empty(array_filter($values[$field]))) {
                $value = $values[$field];
            }
        }
        return $value;
    }

    /**
     * Вернет отреднговое знание из ТВ параметра
     * @param $resource_id
     * @param $field
     * @return mixed|string
     */
    public function getTvValue($resource_id, $field)
    {
        $value = '';
        if (array_key_exists($resource_id, $this->tv_values)) {
            $tv_id = $this->tv_type_field[$field];
            $values = $this->tv_values[$resource_id];
            if (array_key_exists($tv_id, $values)) {
                $tvType = $this->tv_type[$tv_id];
                $tvType->set('resourceId', $resource_id);
                $tvType->set('value', $values[$tv_id]);
                $value = $tvType->renderOutput($resource_id);
            }

        }
        return $value;
    }


    /**
     * Вернет список ресурсов по ID
     * @param $ids
     * @return array
     */
    public function getResources($ids)
    {
        $resources = null;
        $q = $this->modx->newQuery($this->class_key);
        $q->select($this->modx->getSelectColumns($this->class_key, $this->class_key));
        $q->where(array(
            'id:IN' => $ids
        ));

        if ($this->class_key == 'msProduct') {
            $q->select($this->modx->getSelectColumns('msProductData', 'Data'));
            $q->innerJoin('msProductData', 'Data');
        }

        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $resources[] = $row;
            }
        }
        return $resources;
    }


}