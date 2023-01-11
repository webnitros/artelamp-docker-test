<?php
include_once dirname(dirname(dirname(__FILE__))) . '/blocked/ip.class.php';
class antiBotGuestBlockedIpProcessor extends antiBotBlockedIpProcessor
{
    public $objectType = 'antiBotGuest';
    public $classKey = 'antiBotGuest';
    public $nameList = 'guests';
}

return 'antiBotGuestBlockedIpProcessor';