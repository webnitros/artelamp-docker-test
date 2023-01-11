<?php

/**
 * Class MyControllerOrdersLogs
 * Вернет список заказов которые необходимо отслеживать
 */
class MyControllerOrdersOrder extends ApiInterface
{
    public $protected = true;
    public function get()
    {
        $this->post();
    }
    public function post()
    {
        $rows = [];
        $q = $this->modx->newQuery('msOrder');
        $q->select('id,num,createdon,cost,order_1c_id,order_in_1c');
        $q->where(array(
            'track_order' => true,
            'is_send_admin:!=' => 1,
        ));
        $q->limit(100);
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row;
            }
        }
        $this->collection($rows, count($rows));
    }

}
