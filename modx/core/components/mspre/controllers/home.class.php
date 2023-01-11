<?php
if (!class_exists('mspreProductManagerController')) {
    require_once dirname(__FILE__) . '/product.class.php';
}

/**
 * The home manager controller for mspre.
 */
class mspreHomeManagerController extends mspreProductManagerController
{

    /**
     * @return void
     */
    public function initialize()
    {
        // Требуется делать редирект для старых версий компонета
        $this->modx->sendRedirect($this->modx->config['manager_url'] . '?a=product&namespace=mspre');
    }
}