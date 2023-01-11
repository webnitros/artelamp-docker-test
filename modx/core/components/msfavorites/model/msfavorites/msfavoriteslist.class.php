<?php
class msFavoritesList extends xPDOObject {

	public static function load(xPDO & $xpdo, $className, $criteria, $cacheFlag= true) {

		$instance = parent::load($xpdo, 'msFavoritesList', $criteria, $cacheFlag);

		if(!is_object($instance) || !($instance instanceof $className)) {
			if (is_numeric($criteria) || (is_array($criteria) && !empty($criteria['user_id']))) {
				$id = is_numeric($criteria) ? $criteria : $criteria['user_id'];
				$msf_id = $criteria['msf_id'];
				$list = $criteria['list'];
				$properties = $criteria['properties'];
				if (!$xpdo->getCount('msFavoritesList', array('user_id' => $id, 'msf_id' => $msf_id, 'list' => $list))) {
					$instance = $xpdo->newObject('msFavoritesList');
					$instance->set('user_id', $id);
					$instance->set('msf_id', $msf_id);
					$instance->set('list', $list);
					$instance->set('properties', $properties);
					$instance->save();
				}
			}
		}
		return $instance;
	}

}