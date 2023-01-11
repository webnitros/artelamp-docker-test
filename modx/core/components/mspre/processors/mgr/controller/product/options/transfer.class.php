<?php
include_once dirname(__FILE__).'/default.php';

/**
 * Multiple a msProduct
 */
class modmsProductMultipleUpdateTransferProcessor extends modmsProductMultipleUpdateOptionsDefaultProcessor
{
    public function initialize()
    {
        $this->setProperty('field', $this->getProperty('target_value'));
        $this->setProperty('increase', 'transfer');
        $in = parent::initialize();
        if ($in !== true) {
            return $in;
        }

        $price = $this->getProperty($this->fieldUpdate, 0);
        $this->setProperty('price', $price);
        return parent::initialize();
    }

    /* @inheritdoc */
    public function preapreData($id)
    {
        return $this->updatePrice($id);
    }

    /**
     * Увеличение или уменьшение цены в процентах или к целому числу
     * @param int $id
     * @return float
     */
    public function updatePrice($id)
    {
        $source = $this->getProperty('source_value', null);
        $target = $this->getProperty('target_value', null);

        $new_value = null;
        $old_value = null;
        if ($object = $this->modx->getObject('msProduct', $id)) {
            $new_value = $object->get($source);
            $old_value = $object->get($target);
        }

        // Запись операции по установке цен
        $this->transactionRecord($old_value, $new_value);
        return $new_value;
    }


    /**
     * Запись операций с ценами
     * @param float|int $oldValue
     * @param float|int $newValue
     * @return boolean
     */
    public function transactionRecord($oldValue = 0, $newValue = 0)
    {
        $this->transaction = array(
            'field' => $this->fieldUpdate,
            'updatedon' => time(),
            'round' => '',
            'increase' => 'transfer',
            'oldValue' => $oldValue,
            'newValue' => $newValue,
        );
        return true;
    }

    /**
     * Запись операций с ценами
     * @param float $oldprice
     * @param float $newprice
     * @return float
     */
    public function rollbackOperation($oldprice, $newprice)
    {
    }


}

return 'modmsProductMultipleUpdateTransferProcessor';