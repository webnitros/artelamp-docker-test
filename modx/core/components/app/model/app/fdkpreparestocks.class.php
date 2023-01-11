<?php

class fdkPrepareStocks extends xPDOSimpleObject
{
    public function isUpdateError()
    {
        return $this->get('update_error');
    }

    public function getStock()
    {
        return $this->get('stock');
    }
    public function inStock()
    {
        return $this->get('stock') != 0;
    }
}