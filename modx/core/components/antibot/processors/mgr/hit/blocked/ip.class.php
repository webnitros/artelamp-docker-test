<?php
include_once dirname(dirname(dirname(__FILE__))) . '/blocked/ip.class.php';
class antiBotHitsBlockedIpProcessor extends antiBotBlockedIpProcessor
{
    public $objectType = 'antiBotHits';
    public $classKey = 'antiBotHits';
    public $nameList = 'hits';
}

return 'antiBotHitsBlockedIpProcessor';