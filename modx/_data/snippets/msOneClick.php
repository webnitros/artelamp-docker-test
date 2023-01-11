id: 36
source: 1
name: msOneClick
description: 'msOneClick snippet'
category: msOneClick
properties: 'a:13:{s:8:"selector";a:7:{s:4:"name";s:8:"selector";s:4:"desc";s:24:"msoneclick_prop_selector";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:8:"oneClick";s:7:"lexicon";s:21:"msoneclick:properties";s:4:"area";s:0:"";}s:6:"tplBtn";a:7:{s:4:"name";s:6:"tplBtn";s:4:"desc";s:22:"msoneclick_prop_tplBtn";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:18:"tpl.msoneclick.btn";s:7:"lexicon";s:21:"msoneclick:properties";s:4:"area";s:0:"";}s:8:"tplModal";a:7:{s:4:"name";s:8:"tplModal";s:4:"desc";s:24:"msoneclick_prop_tplModal";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:20:"tpl.msoneclick.modal";s:7:"lexicon";s:21:"msoneclick:properties";s:4:"area";s:0:"";}s:7:"tplForm";a:7:{s:4:"name";s:7:"tplForm";s:4:"desc";s:23:"msoneclick_prop_tplForm";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:19:"tpl.msoneclick.form";s:7:"lexicon";s:21:"msoneclick:properties";s:4:"area";s:0:"";}s:14:"tplSendSuccess";a:7:{s:4:"name";s:14:"tplSendSuccess";s:4:"desc";s:30:"msoneclick_prop_tplSendSuccess";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:19:"tpl.msoneclick.send";s:7:"lexicon";s:21:"msoneclick:properties";s:4:"area";s:0:"";}s:14:"tplMAILmessage";a:7:{s:4:"name";s:14:"tplMAILmessage";s:4:"desc";s:30:"msoneclick_prop_tplMAILmessage";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:28:"tpl.msoneclick.email.message";s:7:"lexicon";s:21:"msoneclick:properties";s:4:"area";s:0:"";}s:6:"method";a:7:{s:4:"name";s:6:"method";s:4:"desc";s:22:"msoneclick_prop_method";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:2:"MS";s:7:"lexicon";s:21:"msoneclick:properties";s:4:"area";s:0:"";}s:14:"default_images";a:7:{s:4:"name";s:14:"default_images";s:4:"desc";s:30:"msoneclick_prop_default_images";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:50:"/assets/components/minishop2/img/web/ms2_small.png";s:7:"lexicon";s:21:"msoneclick:properties";s:4:"area";s:0:"";}s:20:"field_required_class";a:7:{s:4:"name";s:20:"field_required_class";s:4:"desc";s:36:"msoneclick_prop_field_required_class";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:20:"msoc_field__required";s:7:"lexicon";s:21:"msoneclick:properties";s:4:"area";s:0:"";}s:17:"email_method_mail";a:7:{s:4:"name";s:17:"email_method_mail";s:4:"desc";s:33:"msoneclick_prop_email_method_mail";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:21:"msoneclick:properties";s:4:"area";s:0:"";}s:10:"returnForm";a:7:{s:4:"name";s:10:"returnForm";s:4:"desc";s:26:"msoneclick_prop_returnForm";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";s:21:"msoneclick:properties";s:4:"area";s:0:"";}s:14:"enable_captcha";a:7:{s:4:"name";s:14:"enable_captcha";s:4:"desc";s:30:"msoneclick_prop_enable_captcha";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";s:21:"msoneclick:properties";s:4:"area";s:0:"";}s:14:"prefix_enabled";a:7:{s:4:"name";s:14:"prefix_enabled";s:4:"desc";s:30:"msoneclick_prop_prefix_enabled";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";s:21:"msoneclick:properties";s:4:"area";s:0:"";}}'
static_file: core/components/msoneclick/elements/snippets/msoneclick.php

-----

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