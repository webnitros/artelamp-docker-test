<?php

$msFavorites = $modx->getService('msfavorites','msFavorites',$modx->getOption('msfavorites_core_path',null,$modx->getOption('core_path').'components/msfavorites/').'model/msfavorites/',$scriptProperties);
if (!($msFavorites instanceof msFavorites)) return '';

$eventName = $modx->event->name;
if ( method_exists( $msFavorites, $eventName ) ) {
	$eventName = lcfirst($eventName);
	$msFavorites->$eventName( $scriptProperties );
}


