<?php
include_once dirname(__FILE__).'/default.php';

/**
 * Multiple a msProduct
 */
class modmsProductMultipleUpdatePriceProcessor extends modmsProductMultipleUpdateOptionsDefaultProcessor
{
    public function initialize()
    {
        $in = parent::initialize();
        if ($in !== true) {
            return $in;
        }

        $increase = $this->getProperty('increase', null);
        if (empty($increase)) {
            return $this->modx->lexicon('mspre_err_increase');
        }
        switch ($increase) {
            case 'new':
                break;
            case 'percent_up':
            case 'percent_down':
                $price = (int)$this->getProperty($this->fieldUpdate, false);
                if ($price > 100000 or $price < 1) {
                    return $this->modx->lexicon('mspre_err_increase_percent');
                }
                break;
            case 'side_up':
            case 'side_down':
                break;
            default:
                return $this->modx->lexicon('mspre_err_increase_percent_not_exist');
                break;
        }


        $round = $this->getProperty('round', null);
        switch ($round) {
            case '':
            case 'round':
            case 'ceil':
                break;
            default:
                return $this->modx->lexicon('mspre_err_round_not_exist');
                break;
        }


        $price = $this->getProperty($this->fieldUpdate, 0);
        $price = (float)str_ireplace(',', '.', $price);
        if (!is_float($price) or (empty($price != 0) and $price != 0)) {
            return $this->modx->lexicon('mspre_err_float');
        }
        $this->setProperty('price', $price);


        return parent::initialize();
    }

    /* @inheritdoc */
    public function preapreData($id)
    {
        $price = $this->getProperty($this->fieldUpdate, 0);
        $price = $this->updatePrice($id, $price);
        return (float)$price;
    }

    /**
     * Увеличение или уменьшение цены в процентах или к целому числу
     * @param int $id
     * @param float $value
     * @return float
     */
    public function updatePrice($id, $value)
    {

        $increase = $this->getProperty('increase', null);


        $newPrice = $value;
        $oldPrice = 0;
        $percent = $value;
        if ($object = $this->modx->getObject('msProduct', $id)) {
            $oldPrice = $object->get($this->fieldUpdate);
            if (strripos($oldPrice, ',') !== false) {
                $oldPrice = (float)str_ireplace(',', '.', $oldPrice);
            }
        }

        switch ($increase) {
            case 'new': break;
            case 'percent_up':
            case 'percent_down':
                if ($oldPrice != 0) {
                    if ($increase == 'percent_up') {
                        $newPrice = $this->percent($oldPrice, $percent);
                    }
                    if ($increase == 'percent_down') {
                        $newPrice = $this->percent($oldPrice, $percent, false);
                    }
                }
                break;
            case 'side_up':

                if ($oldPrice == 0) {
                    $newPrice = $percent;
                } else {
                    $newPrice = $oldPrice + $percent;
                }

                break;
            case 'side_down':

                if ($oldPrice == 0) {
                    $newPrice = $percent;
                } else if ($percent < $oldPrice) {
                    $newPrice = $oldPrice - $percent;
                } else {
                    $newPrice = 0;
                }

                break;
            default:
                $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('mspre_err_increase_percent_not_exist'), '', __METHOD__, __FILE__, __LINE__);
                return false;
                break;
        }


        if ($increase != 'new') {
            $newPrice = $this->updateRound($newPrice);
        }

        // Запись операции по установке цен
        $this->transactionRecord($oldPrice, $newPrice);
        return $newPrice;
    }


    /**
     * Запись операций с ценами
     * @param float|int $oldValue
     * @param float|int $newValue
     * @return boolean
     */
    public function transactionRecord($oldValue = 0,  $newValue = 0)
    {
        $round = $this->getProperty('round');
        $increase = $this->getProperty('increase');
        $this->transaction = array(
            'field' => $this->fieldUpdate,
            'updatedon' =>  time(),
            'round' => $round,
            'increase' => $increase,
            'oldValue' => $oldValue,
            'newValue' => $newValue,
        );
        return true;
    }

    function percent($number, $percent, $plus = true)
    {
        $number_percent = $number / 100 * $percent;
        if ($plus) {
            return $number + $number_percent;
        } else {
            return $number - $number_percent;
        }
    }

    /**
     * Округление цены в меньшую или в большую сторону
     * @param float $price
     * @return float
     */
    public function updateRound($price)
    {
        $round = $this->getProperty('round', null);
        switch ($round) {
            case 'round':
                $price = round($price);
                break;
            case 'ceil':
                $price = ceil($price);
                break;
            default:
                break;
        }
        return $price;
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

return 'modmsProductMultipleUpdatePriceProcessor';