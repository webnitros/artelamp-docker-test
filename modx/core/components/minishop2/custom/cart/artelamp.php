<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 19.11.2020
 * Time: 12:43
 */

if (!class_exists('msDeliveryInterface')) {
    require_once dirname(dirname(dirname(__FILE__))) . '/model/minishop2/msdeliveryhandler.class.php';
}

class fdkExpressMoskov extends msDeliveryHandler implements msDeliveryInterface
{

}