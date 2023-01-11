<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 21.03.2021
 * Time: 15:34
 */

namespace minishop;

use MODxProcessorTestCase;
use PDO;

class PaymentCheck1c extends MODxProcessorTestCase
{
    /**
     * Проверяем чтобы небыло заказов которые небыли переданы в 1с если они были оплачены
     */
    public function testSiteName()
    {
        $rows = [];
        $q = $this->modx->newQuery('msOrder');
        $q->select($this->modx->getSelectColumns('msOrder', 'msOrder'));
        $q->where(array(
            'is_send_admin:!=' => 1,
            'track_order' => 1,
            'payment' => 10,
            'order_payment_sent_in_1c:!=' => true,
        ));
        $q->innerJoin('msOrderLog', 'Log', 'Log.order_id = msOrder.id AND action = "status" AND entry = "2"');
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row;
            }
        }
        self::assertCount(0, $rows);
    }

}
