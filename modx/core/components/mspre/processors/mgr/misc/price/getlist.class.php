<?php


class modmsprePriceGetListProcessor extends modObjectProcessor
{
    public $languageTopics = array('mspre');

    /** {@inheritDoc} */
    public function process()
    {
        $mspre = $this->modx->getService('mspre', 'mspre', MODX_CORE_PATH.'components/mspre/model/');

        $fields = array_map('trim',explode(',', $mspre->getOption('mspre_field_price', null, '')));
        $array = array();
        foreach ($fields as $field) {
            $array[] = array(
                'name' => $field,
                'value' => $field
            );
        }

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
                    'name' => $this->modx->lexicon('mspre_price_all'),
                    'value' => ''
                )
            ), $array);
        }

        return parent::outputArray($array, $count);
    }

}

return 'modmsprePriceGetListProcessor';