id: 50
source: 1
name: redirect301
properties: 'a:0:{}'

-----

header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".$url);
    exit();