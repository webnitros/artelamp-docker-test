<?php
include_once dirname(dirname(dirname(__FILE__))) . '/blocked/useragent.class.php';
class antiBotHitsBlockedUserAgentProcessor extends antiBotBlockedUserAgentProcessor
{
    public $objectType = 'antiBotHits';
    public $classKey = 'antiBotHits';
    public $nameList = 'hits';
}
return 'antiBotHitsBlockedUserAgentProcessor';