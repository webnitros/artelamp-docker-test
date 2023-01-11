<?php

class modmsProductImageGenerationMultipleProcessor extends modObjectProcessor
{
    public function process()
    {

        $id = $this->getProperty('id');

        if (empty($id)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        };

        /* @var msProductFile $file */
        $q = $this->modx->newQuery('msProductFile');
        $q->where(array(
            'parent' => 0,
            'product_id' => $id
        ));
        if ($objectList = $this->modx->getCollection('msProductFile', $q)) {
            foreach ($objectList as $file) {
                $children = $file->getMany('Children');
                /** @var msProductFile $child */
                foreach ($children as $child) {
                    $child->remove();
                }
                $file->generateThumbnails();
            }
        }

        $this->thumbFirst($id);

        return $this->success();
    }

    /**
     * Метод устанавливает в поле thumb первое сгенерированное изображение
     * @param $product_id
     */
    public function thumbFirst($product_id)
    {

        $firstNameSize = 'small';

        $table = $this->modx->getTableName('msProductData');

        $q = $this->modx->newQuery('msProductFile');
        $q->where(array(
            'rank' => 0,
            'product_id' => $product_id,
        ));
        $q->groupby('product_id');
        $q->select("product_id,GROUP_CONCAT(url separator '||') as urls ");
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $product_id = $row['product_id'];
                $urls = $row['urls'];
                if (!empty($urls)) {

                    // Разбираем сгруперовонное поле
                    $urls = explode('||', $urls);

                    $thumb = false;

                    // Ищим часть пути в url
                    $find = '/' . $product_id . '/' . $firstNameSize . '/';
                    foreach ($urls as $url) {
                        if (strripos($url, $find) !== false) {
                            $thumb = $url;
                            break;
                        }
                    }

                    // Обновляем изображение у товара
                    if ($thumb) {
                        $sql = "UPDATE {$table} SET thumb = '{$thumb}' WHERE id = {$product_id}";
                        $this->modx->exec($sql);
                    }
                }
            }
        }
    }
}

return 'modmsProductImageGenerationMultipleProcessor';
