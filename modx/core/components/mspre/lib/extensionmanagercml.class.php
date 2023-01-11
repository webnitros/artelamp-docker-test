<?php


class extensionManagerCml
{
    /* @var modX|null $modx */
    protected $modx = null;
    private $metaTree = array(
        'modResource' => array(
            'fieldMeta' => array(
                'catalog' => array(
                    'dbtype' => 'int',
                    'precision' => '10',
                    'attributes' => 'unsigned',
                    'phptype' => 'integer',
                    'null' => true,
                    'default' => 0,
                ),
                'uuid' => array(
                    'dbtype' => 'char',
                    'precision' => 36,
                    'phptype' => 'string',
                    'null' => true,
                    'default' => '',
                )
            ),
            'indexes' => array(
                'uuid' => array(
                    'alias' => 'uuid',
                    'primary' => false,
                    'unique' => false,
                    'type' => 'BTREE',
                    'columns' =>
                        array(
                            'uuid' =>
                                array(
                                    'length' => '',
                                    'collation' => 'A',
                                    'null' => false,
                                ),
                        ),
                ),
                'catalog' => array(
                    'alias' => 'catalog',
                    'primary' => false,
                    'unique' => false,
                    'type' => 'BTREE',
                    'columns' =>
                        array(
                            'catalog' =>
                                array(
                                    'length' => '',
                                    'collation' => 'A',
                                    'null' => false,
                                ),
                        ),
                )
            )
        ),
        'msopModification' => array(
            'fieldMeta' => array(
                'editedon' => array(
                    'dbtype' => 'int',
                    'precision' => '20',
                    'phptype' => 'timestamp',
                    'null' => false,
                    'default' => 0,
                ),
                'catalog' => array(
                    'dbtype' => 'int',
                    'precision' => '10',
                    'attributes' => 'unsigned',
                    'phptype' => 'integer',
                    'null' => true,
                    'default' => 0,
                ),
                'deleted' => array(
                    'dbtype' => 'tinyint',
                    'precision' => '1',
                    'phptype' => 'integer',
                    'null' => true,
                    'default' => 0,
                ),
            ),
            'indexes' => array(
                'editedon' => array(
                    'alias' => 'editedon',
                    'primary' => false,
                    'unique' => false,
                    'type' => 'BTREE',
                    'columns' =>
                        array(
                            'editedon' =>
                                array(
                                    'length' => '',
                                    'collation' => 'A',
                                    'null' => false,
                                ),
                        ),
                ),
                'catalog' => array(
                    'alias' => 'catalog',
                    'primary' => false,
                    'unique' => false,
                    'type' => 'BTREE',
                    'columns' =>
                        array(
                            'catalog' =>
                                array(
                                    'length' => '',
                                    'collation' => 'A',
                                    'null' => false,
                                ),
                        ),
                ),
                'deleted' => array(
                    'alias' => 'deleted',
                    'primary' => false,
                    'unique' => false,
                    'type' => 'BTREE',
                    'columns' =>
                        array(
                            'deleted' =>
                                array(
                                    'length' => '',
                                    'collation' => 'A',
                                    'null' => false,
                                ),
                        ),
                ),
            )
        ),
    );

    /**
     * msCmlGenerateMapFields constructor.
     * @param modX $modx
     */
    function __construct(modX &$modx)
    {
        $this->modx = $modx;
    }

    /**
     * @param $class
     * @return bool|array
     */
    private function getMeta($class)
    {
        if (isset($this->metaTree[$class])) {
            return $this->metaTree[$class];
        }
        return false;
    }

    public function loadFieldsModifications($metadata)
    {
        /* @var msCmlFieldExtension $object */
        $q = $this->modx->newQuery('msCmlFieldExtension');
        $q->where(array(
            'modification' => 1
        ));
        if ($objectList = $this->modx->getCollection('msCmlFieldExtension', $q)) {
            foreach ($objectList as $object) {
                $name = $object->get('name');
                $meta = $object->getMeta();
                $metadata['fieldMeta'][$name] = $meta;
                if ($object->isIndexes()) {
                    $metadata['indexes'][$name] = $object->getIndexData();
                }
            }
        }
        return $metadata;
    }

    /**
     * @param $class
     * @return bool|array
     */
    public function classExtension($class)
    {
        if ($meta = $this->getMeta($class)) {
            $this->modx->loadClass($class);

            if ($class == 'msopModification') {
                $meta = $this->loadFieldsModifications($meta);
            }


            if (isset($meta['fieldMeta']) and count($meta['fieldMeta']) > 0) {
                foreach ($meta['fieldMeta'] as $field => $options) {
                    if (!isset($this->modx->map[$class]['fields'][$field])) {
                        $this->modx->map[$class]['fields'][$field] = '';
                        $this->modx->map[$class]['fieldMeta'][$field] = $options;
                    }
                }
            }
            if (isset($meta['indexes']) and count($meta['indexes']) > 0) {
                foreach ($meta['indexes'] as $field => $options) {
                    if (!isset($this->modx->map[$class]['indexes'][$field])) {
                        $this->modx->map[$class]['indexes'][$field] = $options;
                    }
                }
            }
        }
        return false;
    }


    /**
     * @param string $class
     * @return bool
     */
    public function createFields($class)
    {
        if ($meta = $this->getMeta($class)) {
            $meta = $this->metaTree[$class];
            $this->classExtension($class);

            $manager = $this->modx->getManager();

            // 1. Add field database
            $fieldMetaData = $meta['fieldMeta'];
            if (is_array($fieldMetaData) and count($fieldMetaData) > 0) {

                $tableFields = [];
                $c = $this->modx->prepare("SHOW COLUMNS IN {$this->modx->getTableName($class)}");
                $c->execute();
                while ($cl = $c->fetch(PDO::FETCH_ASSOC)) {
                    $tableFields[$cl['Field']] = $cl['Field'];
                }


                foreach ($fieldMetaData as $field => $options) {
                    if (in_array($field, $tableFields)) {
                        /*unset($tableFields[$field]);
                        if (!$manager->alterField($class, $field, $options)) {
                            return false;
                        }*/
                    } else {
                        if (!$response = $manager->addField($class, $field, $options)) {
                            return false;
                        }
                    }
                }
            }

            // 2. Operate with indexes
            $indexesData = $meta['indexes'];
            if (is_array($indexesData) and count($indexesData) > 0) {

                // 2. Operate with indexes
                $indexes = [];
                $c = $this->modx->prepare("SHOW INDEX FROM {$this->modx->getTableName($class)}");
                $c->execute();
                while ($row = $c->fetch(PDO::FETCH_ASSOC)) {
                    $name = $row['Key_name'];
                    if (!isset($indexes[$name])) {
                        $indexes[$name] = [$row['Column_name']];
                    } else {
                        $indexes[$name][] = $row['Column_name'];
                    }
                }
                foreach ($indexes as $name => $values) {
                    sort($values);
                    $indexes[$name] = implode(':', $values);
                }


                // Add or alter existing
                foreach ($indexesData as $key => $index) {
                    ksort($index['columns']);
                    $index = implode(':', array_keys($index['columns']));
                    if (!isset($indexes[$key])) {
                        if (!$manager->addIndex($class, $key)) {
                            return false;
                        }
                    } else {
                        /*if ($index != $indexes[$key]) {
                            if (!$manager->removeIndex($class, $key) && $manager->addIndex($class, $key)) {
                                return false;
                            }
                        }*/
                    }
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Генерация дополнительных таблиц
     */
    public function generationMap($service, $action)
    {
        if (method_exists($this, $service)) {
            return $this->{$service}($action);
        }
        return true;
    }

    /**
     * Генерация дополнительных таблиц для modResource
     */
    private function resource($action)
    {
        if ($action == 'create') {
            return $this->createFields('modResource');
        }
        return true;
    }

    /**
     * Генерация дополнительных таблиц для msopModification
     */
    private function msoptionsprice($action)
    {
        /* @var msOptionsPrice $msp */
        if (file_exists(MODX_CORE_PATH . 'components/msoptionsprice/model/msoptionsprice/')) {
            $msp = $this->modx->getService('msoptionsprice', 'msOptionsPrice', MODX_CORE_PATH . 'components/msoptionsprice/model/msoptionsprice/');
            if ($msp instanceof msOptionsPrice) {
                if ($action == 'create') {
                    return $this->createFields('msopModification');
                }
            }
        }
        return true;
    }


}