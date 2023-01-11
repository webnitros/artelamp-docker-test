<?php
class Ms2aConfigData extends xPDOSimpleObject {
	public function set($k = NULL, $v = NULL, $vType = '')
	{
		if (is_array($v) or is_object($v)) {
			$v = @json_encode($v, 256);
		}
		parent::set($k, $v, $vType);
	}
	public function getProperty($k, $default = NULL)
	{
		$v = $this->get($k);
		return (!empty($v) and $v != NULL) ? $v : $default;
	}
}