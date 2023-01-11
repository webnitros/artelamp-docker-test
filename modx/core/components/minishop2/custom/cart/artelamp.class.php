<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 19.11.2020
 * Time: 12:43
 */
if (!class_exists('msCartHandlerInterface')) {
    require_once dirname(__FILE__, 3) . '/model/minishop2/mscarthandler.class.php';
}

class msCartArteLampHandler extends msCartHandler implements msCartInterface
{
    public function status($data = array())
    {
        $status = parent::status($data);
        $status['cart'] = $this->cart;
        return array_merge($data, $status);
    }
}