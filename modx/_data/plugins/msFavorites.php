id: 12
source: 1
name: msFavorites
category: msFavorites
properties: null
static_file: core/components/msfavorites/elements/plugins/plugin.msfavorites.php

-----

$msFavorites = $modx->getService('msfavorites','msFavorites',$modx->getOption('msfavorites_core_path',null,$modx->getOption('core_path').'components/msfavorites/').'model/msfavorites/',$scriptProperties);
if (!($msFavorites instanceof msFavorites)) return '';

$eventName = $modx->event->name;
if ( method_exists( $msFavorites, $eventName ) ) {
	$eventName = lcfirst($eventName);
	$msFavorites->$eventName( $scriptProperties );
}