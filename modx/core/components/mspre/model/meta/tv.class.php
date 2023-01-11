<?php
include_once dirname(__FILE__) . '/default.php';

/**
 * The base class for Nsi.
 */
class mspreMetaTv extends mspreMeta
{
    public $prefix = 'tv';

    /**
     * Загрузка типов
     * @return array|null
     */
    public function loadTypes()
    {
        if (is_null($this->types)) {
            $this->types = $this->mspre->loadData($this->prefix);
            // Установка типов для вывода по умолчанию, для тех у кого не назначено
            if ($fields = $this->loadFields()) {
                foreach ($fields as $field => $type) {
                    if (!isset($this->types[$type])) {
                        $this->types[$type] = array(
                            'id' => '{name}',
                            'dataIndex' => '{name}',
                            'sortable' => false,
                            'editor' => false,
                            'actions' => false,
                        );
                    }
                }
            }
        }


        return $this->types;
    }

    /**
     * @return array|null
     */
    public function loadFields()
    {
        if (is_null($this->fields)) {
            $q = $this->modx->newQuery('modTemplateVar');
            if ($objectList = $this->modx->getCollection('modTemplateVar', $q)) {
                /* @var modTemplateVar $object */
                foreach ($objectList as $object) {
                    $field = $this->getPrefix($object->get('name'));
                    $this->fields[$field] = $object->get('type');
                }
            }
        }

        return $this->fields;
    }


    /**
     * Вернет текст по ключу поля
     * @return null|array
     */
    public function loadCaption()
    {
        if (is_null($this->captions)) {
            $q = $this->modx->newQuery('modTemplateVar');
            $q->select('name,caption');
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $this->captions[$row['name']] = $row['caption'];
                }
            }
        }
        return $this->captions;
    }

    /**
     * Вернет текст по ключу поля
     * @param $key
     * @return null|string
     */
    public function lexicon($key, $pr = null)
    {
        $value = $this->getCaption($key);
        return $this->addPrefixHeader($value, $key, $pr);
    }


}

return 'mspreMetaTv';