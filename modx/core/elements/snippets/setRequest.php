<?php
	$text  = $scriptProperties['text'];
	$key   = $scriptProperties['key'];
	$value = $scriptProperties['value'];
	if (!empty($text)) {
		$q = parse_url($text)['query'];
		$q = explode('&', $q);
		$q = array_map(function ($a) {
			[$key, $value] = explode('=', $a);
			return [
				'key'   => $key,
				'value' => $value,
			];
		}, $q);
		foreach ($q as $req) {
			$_REQUEST[$req['key']] = $req['value'];
			$_GET[$req['key']] = $req['value'];
			$_POST[$req['key']] = $req['value'];
		}
	}
	if (!empty($key)) {
		$_REQUEST[$key] = $value;
		$_GET[$key] = $value;
		$_POST[$key] = $value;
	}

