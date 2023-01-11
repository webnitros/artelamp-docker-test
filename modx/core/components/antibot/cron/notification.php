<?php
define('MODX_API_MODE', true);
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/config.inc.php';
require_once MODX_BASE_PATH . 'index.php';

$antiBot = $modx->getService('antibot', 'antiBot', MODX_CORE_PATH . 'components/antibot/model/');

$start = date('Y-m-d H:i:s', time());
$limit = $modx->getOption('msdeferredmessages_max_limit_message', null, 10);

// Complite
if ($object = $modx->getObject('modSystemSetting',  'msdeferredmessages_last_start_run_crontab')) {
    $object->set('value', $start);
    $object->save();
}

/* @var msDeferredMessagesQueue $queue*/
$q = $modx->newQuery('antiBotGuest');
$q->where(array(
    'dispatch_time:<' => $start
));
$q->limit($limit);
if($objectList = $modx->getCollection('msDeferredMessagesQueue', $q)) {
    foreach ($objectList as $queue) {

        /* @var modProcessorResponse $response */
        $response = $modx->runProcessor('settings/queue/send', $queue->toArray(), array(
            'processors_path' =>  MODX_CORE_PATH.'components/msdeferredmessages/processors/mgr/'
        ));
        if ($response->isError()) {
            return $response->getAllErrors();
        }
        return $response->response;

    }
}
$end = date('Y-m-d H:i:s', time());
// Complite
if ($object = $modx->getObject('modSystemSetting',  'msdeferredmessages_last_end_run_crontab')) {
    $object->set('value', $end);
    $object->save();
}
