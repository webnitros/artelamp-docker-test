id: 33
source: 1
name: UserLocation.initialize
category: userlocation
properties: 'a:3:{s:9:"actionUrl";a:7:{s:4:"name";s:9:"actionUrl";s:4:"desc";s:27:"userlocation_prop_actionUrl";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:24:"[[+assetsUrl]]action.php";s:7:"lexicon";s:23:"userlocation:properties";s:4:"area";s:0:"";}s:8:"frontCss";a:7:{s:4:"name";s:8:"frontCss";s:4:"desc";s:26:"userlocation_prop_frontCss";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:23:"userlocation:properties";s:4:"area";s:0:"";}s:7:"frontJs";a:7:{s:4:"name";s:7:"frontJs";s:4:"desc";s:25:"userlocation_prop_frontJs";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:23:"userlocation:properties";s:4:"area";s:0:"";}}'
static_file: core/components/userlocation/elements/snippets/initialize.php

-----

/** @var array $scriptProperties */
/** @var UserLocation $UserLocation */
if ($UserLocation = $modx->getService('userlocation.UserLocation', '', MODX_CORE_PATH.'components/userlocation/model/')) {
    $UserLocation->initialize($modx->context->key, $scriptProperties);
    $UserLocation->injectScript();
}

return '';