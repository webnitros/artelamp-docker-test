<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.10.2020
 * Time: 17:43
 */
class myCustomFilter extends mse2FiltersHandler
{
    /**
     * Retrieves values from miniShop2 Product table
     *
     * @param array $keys Keys of ms2 products options
     * @param array $ids Ids of needed resources
     *
     * @return array Array with ms2 fields as keys and resources ids as values
     */
    public function getMsOptionValues(array $keys, array $ids)
    {
        $filters = parent::getMsOptionValues($keys, $ids);
        foreach ($filters as $key => $filter) {
            switch ($key){
                case 'color':
                    break;
                default:
                    ksort($filter);
                    break;
            }
            $filters[$key] = $filter;
        }
        return $filters;
    }
}