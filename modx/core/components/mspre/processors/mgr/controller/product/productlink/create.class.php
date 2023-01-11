<?php

class modmsProductProductLinkCreateProcessor extends modObjectGetProcessor
{
    public $classKey = 'msLink';

    public function process()
    {
        $slave = (int)$this->getProperty('slave');


        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->modx->lexicon('mspre_err_ids');
        }

        foreach ($ids as $master) {
            $master = (int)$master;
            // Пропуск установки связей на самого себя
            if ($slave == $master) {
                continue;
            }
            if (!empty($master)) {
                /* @var modProcessorResponse $response */
                if ($response = $this->modx->runProcessor('mgr/product/productlink/create',
                    array(
                        'link' => $this->object->get('id'),
                        'master' => $master,
                        'slave' => $slave,
                    ),
                    array('processors_path' => MODX_CORE_PATH . 'components/minishop2/processors/')
                )
                ) {
                    if ($response->isError()) {
                        $errors = json_encode($response->getAllErrors(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                        return $this->failure($this->modx->lexicon('mspre_err_multisave', array('id' => $master, 'errors' => $errors)), $response->getAllErrors());
                    }
                }

            }
        }

        return $this->success();
    }

}

return 'modmsProductProductLinkCreateProcessor';
