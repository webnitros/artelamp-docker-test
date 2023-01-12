id: 52
source: 1
name: adminLogin
category: AdminTools
properties: 'a:1:{s:3:"tpl";a:7:{s:4:"name";s:3:"tpl";s:4:"desc";s:19:"admintools_prop_tpl";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:14:"tpl.login.form";s:7:"lexicon";s:21:"admintools:properties";s:4:"area";s:0:"";}}'
static_file: core/components/admintools/elements/snippets/snippet.adminlogin.php

-----

/** @var AdminTools $AdminTools */
/** @var array $scriptProperties */
$path = $modx->getOption('admintools_core_path', null, $modx->getOption('core_path') . 'components/admintools/') . 'services/';
$AdminTools = $modx->getService('admintools', 'AdminTools', $path, $scriptProperties);
$get = array_map('trim', $_GET);

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    $success = true;
    $message = $modx->lexicon('admintools_link_is_sent');

    try {
        $AdminTools->sendLoginLink($get);
    } catch (InvalidArgumentException $e) {
        $success = false;
        $message =  $e->getMessage();
    }
    $response = ['success' => $success, 'message' => $message];

    exit($modx->toJSON($response));
}

if ($modx->user->isAuthenticated('mgr')) {
    $modx->sendRedirect($AdminTools->getManagerUrl());
}
$errormsg = '';
if (isset($get['a'], $get['token']) && $get['a'] === 'login') {
    $get['token'] = $modx->sanitizeString($get['token']);
    $data = $AdminTools->getLoginState($get['token']);
    if (!empty($data['uid']) && hash_equals($data['key'], $AdminTools->getUserLoginKey())) {
        $errormsg = $AdminTools->loginUser($data['uid'], $get['token']);
    }
}
/** @var array $scriptProperties */
$assetsUrl = $AdminTools->getOption('assetsUrl');
$modx->regClientCss($assetsUrl . 'css/mgr/login.css');
$modx->regClientScript($assetsUrl . 'js/mgr/login.js');
return $modx->getChunk($tpl, ['errormsg' => $errormsg]);