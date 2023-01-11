id: 39
source: 1
name: bxSubscribe
description: 'bxSender сниппет для подписку на рассылку'
category: bxSender
properties: 'a:2:{s:7:"tplForm";a:7:{s:4:"name";s:7:"tplForm";s:4:"desc";s:21:"bxsender_prop_tplForm";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:27:"tpl.bxSender.Form.Subscribe";s:7:"lexicon";s:19:"bxsender:properties";s:4:"area";s:0:"";}s:8:"tplEmail";a:7:{s:4:"name";s:8:"tplEmail";s:4:"desc";s:22:"bxsender_prop_tplEmail";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:29:"tpl.bxSender.Email.Activation";s:7:"lexicon";s:19:"bxsender:properties";s:4:"area";s:0:"";}}'
static_file: core/components/bxsender/elements/snippets/subscribe.php

-----

/** @var array $scriptProperties */
/** @var bxSender $bxSender */
$bxSender = $modx->getService('bxsender', 'bxSender', $modx->getOption('bxsender_core_path', null, $modx->getOption('core_path') . 'components/bxsender/') . 'model/', $scriptProperties);
if (!($bxSender instanceof bxSender)) return '';
$bxSender->loadController('subscribe');
return $bxSender->loadAction('subscribe/getForm', $scriptProperties);