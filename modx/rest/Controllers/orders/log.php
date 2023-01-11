<?php

/**
 * Class MyControllerOrdersLogs
 * Отдает логи по заказам за 5 последних дней
 * Необходим для построения бизнес процесса что чек отправлен
 * Финальный статус для заказа status payment_send
 */
class MyControllerOrdersLog extends ApiInterface
{
    public $protected = true;

    public function get()
    {
        echo '<pre>';
        print_r(222);
        die;
    }

    public function post()
    {
        $order_id = $this->getProperty('id');
        if (empty($order_id)) {
            return $this->failure('не переда id заказа');
        }

        /* @var msOrder $object */
        if (!$Order = $this->modx->getObject('msOrder', ['num' => $order_id])) {
            return $this->failure('Заказ не найден');
        }


        $statuses = [
            1 => 'new',
            2 => 'paid',
            3 => 'payment_sent',
        ];
        $today = date('Y-m-d', strtotime('-10 days', time()));

        $rows = [];
        $q = $this->modx->newQuery('msOrderLog');
        $q->from('msOrderLog', 'Log');
        $q->select('Log.id,Log.order_id,Log.action,Log.entry,Log.timestamp,Order.cost as cost,Order.num as num');
        $q->select('Order.order_1c_id as order_1c_id,Order.order_in_1c as order_in_1c');
        $q->where(array(
            'Log.timestamp:>' => $today,
            'Log.action' => 'status',
            'Order.id' => $order_id,
            'Order.track_order' => 1, // Онлайн платеж
        ));
        $q->sortby('Log.timestamp', 'DESC');
        $q->innerJoin('msOrder', 'Order', 'Order.id = Log.order_id');
        $q->groupby('Log.id');
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $status = $statuses[$row['entry']];
                $row['status'] = $status;
                $rows[] = $row;
            }
        }
        $this->collection($rows, count($rows));
    }

}
