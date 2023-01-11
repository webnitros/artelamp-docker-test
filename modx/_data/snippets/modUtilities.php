id: 37
source: 1
name: modUtilities
category: modUtilities
snippet: "$method = $scriptProperties['method']??null;\n\t$params = $scriptProperties['params']??null;\n\tif(!is_array($params)){\n\t\t$params = explode(',',$params);\n\t\t$params = array_values($params);\n\t}\n\tif (!empty($method)) {\n\t\t/** @var modX $modx */\n\t\tif (method_exists($modx->util, $method)) {\n\t\t\treturn $modx->util->$method(...$params);\n\t\t} else {\n\t\t\treturn eval('/** @var modX $modx */return $modx->util->' . $method . ';');\n\t\t}\n\t\t$modx->log(MODX::LOG_LEVEL_WARN, 'can`t run $modx->util->' . $method . ' ');\n\n\t}\n\treturn FALSE;"
properties: 'a:0:{}'
static: 1
static_file: core/components/modutilities/elements/snippets/modUtilities.php
content: "$method = $scriptProperties['method']??null;\n\t$params = $scriptProperties['params']??null;\n\tif(!is_array($params)){\n\t\t$params = explode(',',$params);\n\t\t$params = array_values($params);\n\t}\n\tif (!empty($method)) {\n\t\t/** @var modX $modx */\n\t\tif (method_exists($modx->util, $method)) {\n\t\t\treturn $modx->util->$method(...$params);\n\t\t} else {\n\t\t\treturn eval('/** @var modX $modx */return $modx->util->' . $method . ';');\n\t\t}\n\t\t$modx->log(MODX::LOG_LEVEL_WARN, 'can`t run $modx->util->' . $method . ' ');\n\n\t}\n\treturn FALSE;"

-----


$method = $scriptProperties['method']??null;
	$params = $scriptProperties['params']??null;
	if(!is_array($params)){
		$params = explode(',',$params);
		$params = array_values($params);
	}
	if (!empty($method)) {
		/** @var modX $modx */
		if (method_exists($modx->util, $method)) {
			return $modx->util->$method(...$params);
		} else {
			return eval('/** @var modX $modx */return $modx->util->' . $method . ';');
		}
		$modx->log(MODX::LOG_LEVEL_WARN, 'can`t run $modx->util->' . $method . ' ');

	}
	return FALSE;