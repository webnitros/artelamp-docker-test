<?php
define('MODX_CRONTAB_MODE', true); 
require_once '/modx/core/scheduler/index.php'; 
$scheduler->php("order/paymentsent");
$scheduler->process();