id: 15
source: 1
name: msOneClick
category: msOneClick
properties: null
static_file: core/components/msoneclick/elements/plugins/msoneclick.php

-----

/** @var modX $modx */
switch ($modx->event->name) {

    case 'OnHandleRequest':

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';

        if (empty($_REQUEST['msc_action']) ||  (!$isAjax && $modx->event->name != 'OnHandleRequest')) {return;}
        $action = trim($_REQUEST['msc_action']);


        $ctx = !empty($_REQUEST['ctx']) ? (string) $_REQUEST['ctx'] : 'web';
        if ($ctx != 'web') {$modx->switchContext($ctx);}

        if ($ctx == 'mgr' or !isset($_REQUEST['msc_action'])) return;

        if (!empty($_REQUEST['pageId']) && $resource = $modx->getObject('modResource', $_REQUEST['pageId'])) {
            $ctx = $resource->get('context_key');
        } else {
            $ctx = !empty($_REQUEST['ctx']) ? $_REQUEST['ctx'] : 'web';
        }
        if ($ctx != 'web') {
            $modx->switchContext($ctx);
            $modx->user = null;
            $modx->getUser($ctx);
        }

        /* @var msOneClick $msOneClick */
        $msOneClick = $modx->getService('msoneclick', 'msOneClick', $modx->getOption('msoneclick_core_path', null, $modx->getOption('core_path') . 'components/msoneclick/') . 'model/msoneclick/', array());
        if (!($msOneClick instanceof msOneClick)) {
            @session_write_close();
            exit('Could not initialize msOneClick');
        }

        $action = $_REQUEST['msc_action'];
        

        unset($_REQUEST['msc_action']);
        switch ($action){
            case 'form/sendform':
                $response = $msOneClick->loadAction($action, $_REQUEST);
                break;
            case 'form/get':
                $response = $msOneClick->loadAction($action, array('pageId' => @$_POST['pageId'], 'hash' => @$_POST['hash'], 'product_id' => @$_POST['product_id'],'ctx' => @$_POST['ctx'],'method' => @$_POST['method']));
                break;
            case 'form/add':
                $response = $msOneClick->loadAction($action, array('field' => @$_POST['field'], 'value' => @$_POST['value']));
                break;
            default:
                $response = $modx->toJSON(array(
                    'success' => false
                    ,'message' => $modx->lexicon('msoc_err_action_nf')
                ));
                break;
        }

        if (!empty($action)) {
            if ($isAjax) {
                if (is_array($response)) {
                    $response = $modx->toJSON($response);
                }
                @session_write_close();
                exit($response);
            }
        }
        break;
}