<?php

/**
 * Проверка нулевых цен на сайте
 */
class CrontabControllerCheckPrice extends modCrontabController
{

    public function process()
    {
        $rows = null;
        $q = $this->modx->newQuery('msProductData');
        $q->select('id,artikul_1c,price,Product.published as published');
        $q->where(array(
            'Product.published' => 1,
            'Product.deleted:!=' => 1,
            'msProductData.price' => 0,
        ));
        $q->innerJoin('msProduct', 'Product', 'Product.id = msProductData.id');
        $q->limit(20);
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row['artikul_1c'] . '; цена: '.$row['price']. '; id товара: '.$row['id'] .' опубликован ' . $row['published'].' <br>';
            }
        }


        if ($rows) {
            $rows = implode(',', $rows);
            $this->sendMessage('info@bustep.ru', 'Нулевые цены на artelamp.ru', 'Артикулы ' . $rows);
            $this->print_msg('Артикулы ' . $rows);
        }
    }

}
