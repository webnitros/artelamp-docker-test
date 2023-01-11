<?php
include_once dirname(dirname(__FILE__)) . '/process.class.php';

class msExportOrdersExcelCustomOrdersProcessor extends ExportExportProcessor
{
    public $languageTopics = array('msexportordersexcel:default', 'minishop2:default', 'minishop2:manager');


    /* @inheritdoc */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $this->fieldsProperty(array(
            'query' => null,
            'status' => null,
            'customer' => null,
            'context' => null,
            'date_start' => null,
            'date_end' => null
        ));
        $query = trim($this->getProperty('query'));
        if (!empty($query)) {
            if (is_numeric($query)) {
                $c->andCondition(array(
                    'id' => $query,
                    //'OR:User.id' => $query,
                ));
            } else {
                $c->where(array(
                    'num:LIKE' => "{$query}%",
                    'OR:comment:LIKE' => "%{$query}%",
                    'OR:User.username:LIKE' => "%{$query}%",
                    'OR:UserProfile.fullname:LIKE' => "%{$query}%",
                    'OR:UserProfile.email:LIKE' => "%{$query}%",
                ));
            }
        }
        if ($status = $this->getProperty('status')) {
            $c->where(array(
                'status' => $status,
            ));
        }
        if ($customer = $this->getProperty('customer')) {
            $c->where(array(
                'user_id' => (int)$customer,
            ));
        }
        if ($context = $this->getProperty('context')) {
            $c->where(array(
                'context' => $context,
            ));
        }
        if ($date_start = $this->getProperty('date_start')) {
            $c->andCondition(array(
                'createdon:>=' => date('Y-m-d 00:00:00', strtotime($date_start)),
            ), null, 1);
        }
        if ($date_end = $this->getProperty('date_end')) {
            $c->andCondition(array(
                'createdon:<=' => date('Y-m-d 23:59:59', strtotime($date_end)),
            ), null, 1);
        }
        return parent::prepareQueryBeforeCount($c);
    }
}

return 'msExportOrdersExcelCustomOrdersProcessor';