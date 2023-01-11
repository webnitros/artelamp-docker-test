<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 21.03.2021
 * Time: 15:34
 */

class Settings extends MODxProcessorTestCase
{
    /**
     * Проверяем чтобы небыло заказов которые небыли переданы в 1с если они были оплачены
     */
    public function testSiteName()
    {

        
       /* $q = $this->modx->newQuery('CronTabManagerTask');
        $q->select('id');
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->runTask($row['id'], true);
            }
        }*/
        $true = false;
        self::assertTrue($true);
    }

}
