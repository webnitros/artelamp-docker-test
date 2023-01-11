<?php


class mspPayAnyWayMsOnManagerCustomCssJs extends mspPayAnyWayPlugin
{
    public function run()
    {
        $page = $this->modx->getOption('page', $this->scriptProperties);

        switch ($page) {
            case 'settings':
                $this->msppayanyway->loadControllerJsCss($this->modx->controller, array(
                    'config'         => true,
                    'tools'          => true,
                    'payment/inject' => true,
                ));
                break;

            default:
                break;
        }

    }
}