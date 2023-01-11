<?php

class modmsProductImageRemoveMultipleProcessor extends modObjectProcessor
{
    public function process()
    {

        $id = $this->getProperty('id');
        if (empty($id)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        $ids = array();
        /* @var msProductFile $object */
        $q = $this->modx->newQuery('msProductFile');
        $q->where(array(
            'parent' => 0,
            'product_id' => $id
        ));
        $q->select('id');
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $ids[] = $row['id'];
            }
        }
        if (count($ids) > 0) {
            /* @var modProcessorResponse $response */
            $response = $this->modx->runProcessor('mgr/gallery/multiple', array(
                'ids' => $this->modx->toJSON($ids),
                'method' => 'remove',
            ), array(
                'processors_path' => MODX_CORE_PATH . 'components/minishop2/processors/'
            ));
            if ($response->isError()) {
                return $this->failure($response->getMessage());
            }
        }
        return $this->success();
    }
}

return 'modmsProductImageRemoveMultipleProcessor';
