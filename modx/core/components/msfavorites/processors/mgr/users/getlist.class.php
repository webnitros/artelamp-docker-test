<?php
/**
 * Get a list of Lists
 */
class UsersListGetListProcessor extends modObjectGetListProcessor {

    public $objectType = 'msFavoritesList';
    public $classKey = 'msFavoritesList';
    public $defaultSortField = 'msFavoritesList.user_id';
    public $defaultSortDirection = 'DESC';
    public $renderers = '';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin('modUser', 'modUser', 'msFavoritesList.user_id = modUser.id');
        $c->leftJoin('modUserProfile', 'modUserProfile', 'msFavoritesList.user_id = modUserProfile.internalKey');
        $c->select($this->modx->getSelectColumns('msFavoritesList', 'msFavoritesList'));
        $c->select($this->modx->getSelectColumns('modUser', 'modUser', 'user_', array('username')));
        $c->select($this->modx->getSelectColumns('modUserProfile', 'modUserProfile', 'profile_'));
        $c->where(array('msFavoritesList.msf_id'=>$this->getProperty('msf_id')));
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

return 'UsersListGetListProcessor';