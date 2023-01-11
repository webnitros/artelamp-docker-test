<?php
include_once dirname(dirname(__FILE__)) . '/process.class.php';

class ExportUsersCustomUsersProcessor extends ExportExportProcessor
{
    public $languageTopics = array('msexportusersexcel:default');


    /* @inheritdoc */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $this->fieldsProperty(array(
            'usergroup' => false,
            'query' => '',
        ));


        $queryChunks = explode(':', $this->getProperty('query',''));
        if (count($queryChunks) == 2) {
            list($field, $query) = $queryChunks;
            if (in_array($field, array_keys($this->modx->getFields('modUserProfile')))) {
                $c->where(array("Profile.$field:LIKE" => '%'.$query.'%'));
            }
        } else {
            $query = current($queryChunks);
            if (!empty($query)) {
                $c->where(array(
                    $this->classKey . '.username:LIKE' => '%'.$query.'%',
                    'Profile.fullname:LIKE' => '%'.$query.'%',
                    'Profile.email:LIKE' => '%'.$query.'%'
                ), xPDOQuery::SQL_OR);
            }
        }

        $userGroup = $this->getProperty('usergroup',0);
        if (!empty($userGroup)) {
            if ($userGroup === 'anonymous') {
                $c->where(array(
                    'UserGroupMembers.user_group' => NULL,
                ));
            } else {
                $c->distinct();
                $c->where(array(
                    'UserGroupMembers.user_group' => $userGroup,
                ));
            }
        }
        return parent::prepareQueryBeforeCount($c);
    }
}

return 'ExportUsersCustomUsersProcessor';