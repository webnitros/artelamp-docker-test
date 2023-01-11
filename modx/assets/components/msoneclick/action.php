<?php
if (empty($_REQUEST['msc_action']) && empty($_REQUEST['msc_action'])) {
	die('Access denied');
}
if (!empty($_REQUEST['msc_action'])) {$_REQUEST['msc_action'] = $_REQUEST['msc_action'];}

require dirname(dirname(dirname(dirname(__FILE__)))).'/index.php';