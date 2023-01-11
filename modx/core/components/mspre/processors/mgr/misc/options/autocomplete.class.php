<?php

class msProductAutocompleteOptionsProcessor extends modObjectProcessor
{
    /* @var mspreTvField $TvField */
    public $TvField;

    /**
     * @return array|string
     */
    public function process()
    {
        $ids = $this->modx->fromJSON($this->getProperty('ids'));

        ;
        $field = trim($this->getProperty('field'));
        $whatValues = trim($this->getProperty('whatValues')); // possible - Возможные значени | entered - уже занесенные

        $originalField = $field;
        if ($key = prefixFields($field)) {
            $field = $key;
        } else if ($key = prefixOptions($field)) {
            $field = $key;
        }

        $criteria = array(
            'key' => $field,
        );


        if ($whatValues == 'entered') {
            $criteria['product_id:IN'] = $ids;
        }


        if ($query = trim($this->getProperty('query'))) {
            $criteria['value:LIKE'] = "%{$query}%";
        }

        $values = array();
        $q = $this->modx->newQuery('msProductOption');
        $q->select('value AS ' . $field);
        $q->where($criteria);
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $key = $row[$field];
                $data = $row[$field];
                $values[$key] = array(
                    $originalField => $data
                );
            }
        }

        if (count($values) > 0) {
            $values = array_values($values);
        }

        return $this->outputArray($values);
    }

}

return 'msProductAutocompleteOptionsProcessor';