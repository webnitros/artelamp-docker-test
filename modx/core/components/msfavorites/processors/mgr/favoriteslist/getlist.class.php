<?php
/**
 * Get a list of Lists
 */
class FavoritesListGetListProcessor extends modObjectGetListProcessor {

    public $objectType = 'msFavoritesList';
    public $classKey = 'msFavoritesList';
    public $defaultSortField = 'msFavoritesList.msf_id';
    public $defaultSortDirection = 'DESC';
    public $renderers = '';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin('modResource', 'modResource', 'msFavoritesList.msf_id = modResource.id');
        $c->select($this->modx->getSelectColumns('msFavoritesList', 'msFavoritesList'));
        $c->select(array('modResource.pagetitle as name', 'COUNT(msFavoritesList.user_id) AS total'));
        $c->groupby('msf_id');
        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();

        return $array;
    }

}

return 'FavoritesListGetListProcessor';