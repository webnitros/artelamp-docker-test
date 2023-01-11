<?php

class fdkPreparePrices extends xPDOSimpleObject
{

    public function isErrorPrice()
    {
        return $this->get('update_error');
    }

    public function getPrice()
    {
        return $this->get('sale') ? $this->get('price_sale') : $this->get('price');
    }

    public function getOldPrice()
    {
        return $this->get('sale') ? $this->get('price') : $this->get('price_sale');
    }

    public function getSale()
    {
        return $this->get('sale');
    }

    /**
     * Вернет True если в данных что то поменялось
     * @return bool
     */
    public function isChange()
    {
        return ($this->isDirty('price') || $this->isDirty('price_sale') || $this->isDirty('price_sale') || $this->isDirty('update_error'));
    }

}