<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var msOneClick $msOneClick */
$method = $modx->getOption('method', $scriptProperties, 'MS');
if (!empty($scriptProperties['create_order'])) {
    $method = $scriptProperties['create_order'];
}
$show_loader = $modx->getOption('show_loader', $scriptProperties, true);
$selector = $modx->getOption('selector', $scriptProperties, 'oneClick');
$tplBtn = $modx->getOption('tplBtn', $scriptProperties, 'tpl.msOneClick.btn');
$returnForm = (boolean)$modx->getOption('returnForm', $scriptProperties, false);

if (empty($scriptProperties['required_fields'])) {
    unset($scriptProperties['required_fields']);
}

if (!$msOneClick = $modx->getService('msoneclick', 'msOneClick', $modx->getOption('msoneclick_core_path', null, $modx->getOption('core_path') . 'components/msoneclick/') . 'model/msoneclick/', array())) {
    return 'Could not load msOneClick class!';
}
$msOneClick->initialize($modx->context->key, $scriptProperties);


if (empty($id)) {
    if ($modx->resource->class_key == 'msProduct') {
        $id = $modx->resource->id;
    }
}



if (!empty($id)) $id = (int)$id;
if (empty($id) and $method != 'CALLBACK') return $modx->lexicon('msoc_err_snippet_product_id');
if (empty($tplBtn)) return $modx->lexicon('msoc_err_snippet_tpl_btn');

$scriptProperties['timestamp'] = time();
$hash = $msOneClick->getHastBtn($scriptProperties);
$data = array(
    'id' => $id,
    'selector' => $selector,
    'method' => $method,
    'hash' => $hash,
);

if ($returnForm) {
    $pageId = isset($pageId) ? $pageId : $modx->resource->id;
    $ctx = isset($ctx) ? $ctx : $modx->context->key;
    $response = $msOneClick->loadAction('form/get', array('pageId' => $pageId, 'hash' => $hash, 'product_id' => @$id, 'ctx' => $ctx, 'method' => $method));
    $modx->regClientScript(preg_replace(array('/^\n/', '/\t{7}/'), '', '
    <script>
        $(function(){
            msOneClick.hasHash.push("' . $hash . '")
            msOneClick.Form.init("msoneclickForm-' . $hash . '");
            msOneClick.Form.setCount(' . $id . ');
            msOneClick.options.body = "#oneClick_body";
        });
    </script>
    '), true);
    return $response['object']['model'];
}

return $msOneClick->pdoTools->getChunk($tplBtn, $data, $msOneClick->pdoTools->config['fastMode']);