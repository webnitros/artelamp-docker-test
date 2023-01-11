id: 58
source: 1
name: cdn
properties: 'a:0:{}'

-----

if (empty($input)) {
    return '';
}
return getenv('SITE_CDN') . ltrim($input, '/');