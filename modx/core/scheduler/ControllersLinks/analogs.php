<?php
define('MODX_CRONTAB_MODE', true); 
require_once '/modx/core/scheduler/index.php'; 
$scheduler->php("analogs");
$scheduler->process();