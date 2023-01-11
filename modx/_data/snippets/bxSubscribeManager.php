id: 42
source: 1
name: bxSubscribeManager
description: 'bxSender сниппет для управление подпиской'
category: bxSender
properties: 'a:1:{s:7:"tplForm";a:7:{s:4:"name";s:7:"tplForm";s:4:"desc";s:21:"bxsender_prop_tplForm";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:25:"tpl.bxSender.Form.Manager";s:7:"lexicon";s:19:"bxsender:properties";s:4:"area";s:0:"";}}'
static_file: core/components/bxsender/elements/snippets/manager.php

-----

/** @var array $scriptProperties */
/** @var bxSender $bxSender */
$bxSender = $modx->getService('bxsender', 'bxSender', $modx->getOption('bxsender_core_path', null, $modx->getOption('core_path') . 'components/bxsender/') . 'model/', $scriptProperties);
if (!($bxSender instanceof bxSender)) return '';
$bxSender->loadController('manager');
return $bxSender->loadAction('manager/getForm', $scriptProperties);