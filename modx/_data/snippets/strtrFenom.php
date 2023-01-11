id: 35
source: 1
name: strtrFenom
properties: 'a:0:{}'

-----

$string = $modx->getOption('input', $scriptProperties, 0);
$word = $modx->getOption('options', $scriptProperties, '');

$array = ['Arte Lamp'];

array_push($array, $word);

$result = preg_replace('/\b('.implode('|',$array).')\b/','',$string);
if(strtolower($word) === strtolower("Бра")){
    // print_r($string);
    // print_r($array);
    // die;
}

return $result;