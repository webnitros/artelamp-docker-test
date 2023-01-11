<?php
include_once dirname(__FILE__) . '/default.php';

/**
 * The base class for Nsi.
 */
class mspreMetaOptions extends mspreMeta
{
    public $prefix = 'options';

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
            $this->fields = array();

            /* @var msOption $Option */
            $q = $this->modx->newQuery('msOption');
            if ($objectList = $this->modx->getCollection('msOption', $q)) {
                foreach ($objectList as $Option) {
                    $key = $this->getPrefix($Option->get('key'));
                    $this->fields[$key] = $Option->get('type');
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
            $q = $this->modx->newQuery('msOption');
            $q->select('key,caption');
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $this->captions[$row['key']] = $row['caption'];
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

return 'mspreMetaOptions';