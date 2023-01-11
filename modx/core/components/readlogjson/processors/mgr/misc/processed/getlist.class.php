<?php


class ReadLogJsonActiveGetListProcessor extends modObjectProcessor
{
    public $languageTopics = array('readlogjson:manager');

    /** {@inheritDoc} */
    public function process()
    {
        $array = array(
            0 => array(
                'name'  => $this->modx->lexicon('readlogjson_processed'),
                'value' => '1'
            ),
            1 => array(
                'name'  => $this->modx->lexicon('readlogjson_inprocessed'),
                'value' => '0'
            ),
        );

        $query = $this->getProperty('query');
        if (!empty($query)) {
            foreach ($array as $k => $format) {
                if (stripos($format['name'], $query) === false) {
                    unset($array[$k]);
                }
            }
            sort($array);
        }

        return $this->outputArray($array);
    }

    /** {@inheritDoc} */
    public function outputArray(array $array, $count = false)
    {
        if ($this->getProperty('addall')) {
            $array = array_merge_recursive(array(
                array(
                    'name'  => $this->modx->lexicon('readlogjson_all'),
                    'value' => ''
                )
            ), $array);
        }

        return parent::outputArray($array, $count);
    }

}

return 'ReadLogJsonActiveGetListProcessor';
