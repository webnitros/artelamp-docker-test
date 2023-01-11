id: 51
name: page-feed-template
properties: 'a:0:{}'

-----

header("Content-type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=page-feed-template.csv");
header("Pragma: no-cache");
header("Expires: 0");
$q= $modx->query("
SELECT a.id,b.pagetitle from ara3_site_content AS a 
LEFT JOIN ara3_site_content AS b on b.id = a.parent
WHERE a.template = 6
");
$csv = $modx->util->csv();
$csv->setHead('Page URL','Custom label');
while($row = $q->fetch(2)){
   $csv->addRow($modx->makeUrl($row['id'],'', '', 'full'),$row['pagetitle']) ;
}
echo $csv;