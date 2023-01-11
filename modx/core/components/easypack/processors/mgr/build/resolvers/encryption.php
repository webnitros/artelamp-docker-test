<?php
	$transport->xpdo->loadClass('transport.xPDOObjectVehicle', XPDO_CORE_PATH, TRUE, TRUE);
	$transport->xpdo->loadClass('EncryptedVehicle', MODX_CORE_PATH . 'components/' . strtolower($transport->name) . '/model/', TRUE, TRUE);