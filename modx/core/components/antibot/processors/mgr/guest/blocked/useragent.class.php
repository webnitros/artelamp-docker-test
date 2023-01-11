<?php
include_once dirname(dirname(dirname(__FILE__))) . '/blocked/useragent.class.php';
class antiBotGuestBlockedUserAgentProcessor extends antiBotBlockedUserAgentProcessor
{
    public $objectType = 'antiBotGuest';
    public $classKey = 'antiBotGuest';
    public $nameList = 'guests';
}
return 'antiBotGuestBlockedUserAgentProcessor';