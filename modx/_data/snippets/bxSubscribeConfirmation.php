id: 41
source: 1
name: bxSubscribeConfirmation
description: 'bxSender сниппет для подтверждения E-mail'
category: bxSender
properties: 'a:0:{}'
static_file: core/components/bxsender/elements/snippets/confirmation.php

-----

/** @var array $scriptProperties */
/** @var bxSender $bxSender */
$bxSender = $modx->getService('bxsender', 'bxSender', $modx->getOption('bxsender_core_path', null, $modx->getOption('core_path') . 'components/bxsender/') . 'model/', $scriptProperties);
if (!($bxSender instanceof bxSender)) return '';
$bxSender->loadController('subscribe');
return $bxSender->loadAction('subscribe/confirmationEmail', $scriptProperties);