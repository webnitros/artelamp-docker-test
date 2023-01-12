id: 34
source: 1
name: UserLocation.location
category: userlocation
properties: 'a:6:{s:3:"tpl";a:7:{s:4:"name";s:3:"tpl";s:4:"desc";s:21:"userlocation_prop_tpl";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:22:"UserLocation.locations";s:7:"lexicon";s:23:"userlocation:properties";s:4:"area";s:0:"";}s:6:"sortby";a:7:{s:4:"name";s:6:"sortby";s:4:"desc";s:24:"userlocation_prop_sortby";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:4:"name";s:7:"lexicon";s:23:"userlocation:properties";s:4:"area";s:0:"";}s:7:"sortdir";a:7:{s:4:"name";s:7:"sortdir";s:4:"desc";s:25:"userlocation_prop_sortdir";s:4:"type";s:4:"list";s:7:"options";a:0:{}s:5:"value";s:3:"ASC";s:7:"lexicon";s:23:"userlocation:properties";s:4:"area";s:0:"";}s:5:"limit";a:7:{s:4:"name";s:5:"limit";s:4:"desc";s:23:"userlocation_prop_limit";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:300;s:7:"lexicon";s:23:"userlocation:properties";s:4:"area";s:0:"";}s:13:"toPlaceholder";a:7:{s:4:"name";s:13:"toPlaceholder";s:4:"desc";s:31:"userlocation_prop_toPlaceholder";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";s:23:"userlocation:properties";s:4:"area";s:0:"";}s:4:"type";a:7:{s:4:"name";s:4:"type";s:4:"desc";s:22:"userlocation_prop_type";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:4:"city";s:7:"lexicon";s:23:"userlocation:properties";s:4:"area";s:0:"";}}'
static_file: core/components/userlocation/elements/snippets/location.php

-----

//$_SESSION['userlocation.id'] = '';
//echo "<pre>";
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var UserLocation $UserLocation */
if ($UserLocation = $modx->getService('userlocation.UserLocation', '', MODX_CORE_PATH.'components/userlocation/model/')) {
    $UserLocation->initialize($modx->context->key, $scriptProperties);
}

/** @var pdoFetch $pdoFetch */
if (!$modx->loadClass('pdofetch', MODX_CORE_PATH.'components/pdotools/model/pdotools/', false, true)) {
    return false;
}
$pdoFetch = new pdoFetch($modx, $scriptProperties);
$pdoFetch->addTime('pdoTools loaded.');

$class = 'ulLocation';

if (!empty($returnIds)) {
    $return = 'ids';
} elseif (!isset($return) OR $return === '') {
    $return = 'chunk';
}
if (!isset($tpl)) {
    $tpl = '';
}
if (!isset($typeSearch)) {
    $typeSearch = 'local';
}
if (!isset($showUnpublished)) {
    $showUnpublished = '0';
}
if (!isset($limit) OR $limit == '') {
    $limit = 10;
}
if (!isset($additionalPlaceholders) OR $additionalPlaceholders == '') {
    $additionalPlaceholders = 1;
}
if (!isset($outputSeparator)) {
    $outputSeparator = "\n";
}
if (!isset($type)) {
    $type = '';
}
$type = is_array($type) ? $type : array_map('trim', explode(',', $type));
$type = @array_diff($type, ['']);

if (!isset($processParent) OR $processParent == '') {
    $processParent = 1;
}
if (!isset($processLetter) OR $processLetter == '') {
    $processLetter = 1;
}

// Start build "where" expression
$where = [];
if (empty($showUnpublished)) {
    $where[$class.'.active'] = true;
}
if (!empty($type)) {
    $where[$class.'.type:IN'] = $type;
}

// Add grouping
$groupby = [
    $class.'.id',
];

// Join tables
$leftJoin = [
];

$innerJoin = [
];

$select = [
    $class => $modx->getSelectColumns($class, $class, '', [], true),
];

// Add user parameters
foreach (['where', 'leftJoin', 'innerJoin', 'select', 'groupby'] as $v) {
    if (!empty($scriptProperties[$v])) {
        $tmp = $scriptProperties[$v];
        if (!is_array($tmp)) {
            $tmp = json_decode($tmp, true);
        }
        if (is_array($tmp)) {
            $$v = array_merge($$v, $tmp);
        }
    }
    unset($scriptProperties[$v]);
}
$pdoFetch->addTime('Conditions prepared');

$config = array_merge([
    'class'             => $class,
    'where'             => $where,
    'leftJoin'          => $leftJoin,
    'innerJoin'         => $innerJoin,
    'select'            => $select,
    'sortby'            => $class.'.name',
    'sortdir'           => 'ASC',
    'groupby'           => implode(', ', $groupby),
    'nestedChunkPrefix' => 'userlocation_',
], $scriptProperties, ['return' => !empty($returnIds) ? 'ids' : 'data']);
$pdoFetch->setConfig($config, false);
$locations = $pdoFetch->run();

$parents = $letters = [];
// Process rows
if (!empty($locations) AND is_array($locations)) {
    if (!empty($processParent) AND $parentIds = array_column($locations, 'parent')) {
        $config = array_merge([
            'class'     => $class,
            'where'     => [$class.'.id:IN' => $parentIds],
            'leftJoin'  => $leftJoin,
            'innerJoin' => $innerJoin,
            'select'    => $select,
            'sortby'    => $class.'.name',
            'sortdir'   => 'ASC',
            'groupby'   => implode(', ', $groupby),
        ], $scriptProperties, ['return' => 'data']);
        $pdoFetch->setConfig($config, false);
        $parents = $pdoFetch->run();
    }
    if (!empty($processLetter) AND $letterIds = array_column($locations, 'name')) {
        foreach ($locations as $location) {
            $letter = mb_strtoupper(mb_substr($location['name'], 0, 1, 'utf-8'), 'utf-8');
            if (!isset($letters[$letter])) {
                $letters[$letter] = [];
            }
            $letters[$letter][] = $location['id'];
        }
        // sort "letters"
        uksort($letters, function ($a, $b) {
            if (ord($a) > 122 && ord($b) > 122) {
                return $a > $b ? 1 : -1;
            }
            if (ord($a) > 122 || ord($b) > 122) {
                return $a < $b ? 1 : -1;
            }
        });
    }

    // set index "id"
    foreach (['locations', 'parents'] as $k) {
        $rows = isset(${$k}) ? ${$k} : [];
        if (is_array($rows)) {
            $tmp = [];
            foreach ($rows as $row) {
                $tmp[$row['id']] = $row;
            }
            ${$k} = $tmp;
        }
    }
}

$pls = ['letters' => $letters, 'parents' => $parents, 'locations' => $locations];


$output = [];
switch ($return) {
    case 'ids':
        $output = is_string($locations) ? $locations : implode(',', $locations);
        $modx->setPlaceholder('UserLocation.log', $log);
        if (!empty($toPlaceholder)) {
            $modx->setPlaceholder($toPlaceholder, $output);
            $output = '';
        }
        break;
    case 'data':
        $output = $pls;
        break;
    case 'json':
        $output = json_encode($pls, true);
        break;
    default:
        if (!empty($additionalPlaceholders)) {
            $pls = array_merge($scriptProperties, $pls);
        }
        /** @var pdoTools $pdoTools */
        $pdoTools = $modx->getService('pdoTools');
        $output[] = $pdoTools->getChunk($tpl, $pls);
        if (!empty($toSeparatePlaceholders)) {
            $output['log'] = $log;
            $modx->setPlaceholders($output, $toSeparatePlaceholders);
            $output = '';
        } else {
            $output['log'] = $log;
            $output = implode($outputSeparator, $output);
            if (!empty($tplWrapper) && (!empty($wrapIfEmpty) || !empty($output))) {
                $output = $pdoFetch->getChunk($tplWrapper, [
                    'output' => $output,
                ]);
            }
            if (!empty($toPlaceholder)) {
                $modx->setPlaceholder($toPlaceholder, $output);
                $output = '';
            }
        }
        break;
}

return $output;