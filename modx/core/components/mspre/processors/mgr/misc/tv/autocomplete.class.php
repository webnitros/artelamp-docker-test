<?php

class mspreAutocompleteProcessor extends modObjectProcessor
{
    /* @var mspreTvField $TvField */
    public $TvField;

    /**
     * @return array|string
     */
    public function process()
    {
        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        $field = trim($this->getProperty('field'));  // поле тв параметра
        //$template_id = trim($this->getProperty('template'));  // Шаблон объекта с доступными объектами
        $whatValues = trim($this->getProperty('whatValues')); // possible - Возможные значени | entered - уже занесенные


        /* @var modTemplateVar $object */
        if (!$tv = $this->modx->getObject('modTemplateVar', array('name' => $field))) {
            $msg = "Could not found modTemplateVar type Tv {$field}";
            $this->modx->log(modX::LOG_LEVEL_ERROR, $msg, '', __METHOD__, __FILE__, __LINE__);
            return $this->failure($msg);
        }


        if (!$this->TvField = $this->modx->getObject('mspreTvField', array('name' => $tv->get('type')))) {
            $msg = "Could not found mspreTvField type {$tv->get('type')} Tv {$tv->get('name')}";
            $this->modx->log(modX::LOG_LEVEL_ERROR, $msg, '', __METHOD__, __FILE__, __LINE__);
            return $this->failure($msg);
        }

        $this->TvField->setTvObject($tv);

        // Проверка доступа поля для указанного шаблона
        $values = $this->TvField->possibleValues();
        switch ($whatValues) {
            case 'entered': // уже занесенные
                $values = $this->TvField->enteredValues($ids);
                break;
            default:
                break;
        }

        $query = trim($this->getProperty('query'));
        if (!empty($query)) {
            foreach ($values as $k => $format) {
                if (stripos($format['name'], $query) === false) {
                    unset($values[$k]);
                }
            }
            sort($values);
        }

        return $this->outputArray($values);
    }

}

return 'mspreAutocompleteProcessor';