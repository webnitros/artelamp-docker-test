id: 11
source: 1
name: msAddField
category: msAddField
properties: null
static_file: core/components/msaddfield/elements/plugins/msaddfield.php

-----

/** @var modX $modx */
/* @var array $scriptProperties */
switch ($modx->event->name) {
    case 'OnMODXInit':
    case 'OnHandleRequest':
        /* @var msAddField $msAddField*/
        $msAddField = $modx->getService('msaddfield', 'msAddField', $modx->getOption('msaddfield_core_path', $scriptProperties, $modx->getOption('core_path') . 'components/msaddfield/') . 'model/');
        if ($msAddField instanceof msAddField) {
            $msAddField->loadHandlerEvent($modx->event, $scriptProperties);
        }
        break;
}
return '';