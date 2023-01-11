{var $assets_source = '/inc/'}
{var $assets_version = '?v='~'assets_version' | option}
<meta charset="{$modx->config.modx_charset}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="">
<base href="{$modx->config.site_url}" />
<meta name="p:domain_verify" content="a4e38f89462a73fd5ad0f1c8f1d1d3e2"/>
{var $class_key = $_modx->resource.class_key}
{if $class_key === 'msProduct'}
    {var $resource = $_modx->resource}
    {var $collection = $resource['collection'] ? $resource['collection']:$resource['collection_web']}
    {var $pagetitle = $_modx->resource.pagetitle|replace:$resource.article:'' }
    {var $pagetitle = $pagetitle|replace:$resource.vendor_code:'' }
    {var $pagetitle = trim($pagetitle|replace:$collection:'') }
    <title>{$resource.pagetitle} — купить на официальном сайте</title>
    <meta name="description" content="Купить {$pagetitle|lcase} {$resource.article} от торговой марки Artelamp по 💸 минимальной цене с ⭐ гарантией качества и ✅ быстрой доставкой по Москве и России.">
    <meta name="keywords" content="{$collection} {$resource.article}">
{else}
    {if $_modx->resource.id}
        {if $.get.page}
            <title>ARTE Lamp — {$_modx->resource.pagetitle} | Страница {$.get.page}</title>
        {else}
            <title>ARTE Lamp — {$_modx->resource.pagetitle}</title>
        {/if}
    {else}
        {if $.get.page}
            <title>ARTE Lamp — {$modx->runSnippet("pdoTitle")} / {$modx->config.site_name} | Страница {$.get.page}</title>
        {else}
            <title>ARTE Lamp — {$modx->runSnippet("pdoTitle")} / {$modx->config.site_name}</title>
        {/if}
    {/if}
    {if $class_key == 'msCategory'}
        {var $pagetitle = $_modx->resource.pagetitle}
        <meta name="description" content="{$pagetitle}. Гарантия производителя и запчасти">
        <meta name="keywords" content="{$pagetitle}">
        {if $.get.page}
            <link rel="canonical" href="{$modx->resource->id|url}?page={$.get.page}"/>
        {else}
            <link rel="canonical" href="{$modx->resource->id|url}"/>
        {/if}
    {else}
        <meta name="description" content="">
        <link rel="canonical" href="{$modx->resource->id|url}"/>
    {/if}
{/if}




<link type="image/x-icon" rel="shortcut icon" href="/favicon.ico">

{'css/fonts.css'|css}
{'css/bootstrap.min.css'|css}
{'css/reset.css'|css}
{'css/nice-select.css'|css}
{'css/jquery.mCustomScrollbar.css'|css}
{'css/nouislider.css'|css}
{'css/style.css'|css}
{'css/swiper.css'|css}
{'css/responsive.css'|css}
{'css/apply_filters.css'|css}
{'css/search_list.css'|css}
{'css/style_custom.css'|css}
{'css/cart.css'|css}
{'css/card.css'|css}
{'css/css.css'|css}
{'css/textpage.css'|css}
{$_modx->regClientStartupScript($assets_source~"js/jquery-1.11.3.min.js" ~ $assets_version)}

{var $mode_dev = 'mode_dev'|placeholder}
{if !$mode_dev}
    <script>
        mindbox = window.mindbox || function() { mindbox.queue.push(arguments); };
        mindbox.queue = mindbox.queue || [];
        mindbox('create');
    </script>
    <script async src="https://api.mindbox.ru/scripts/v1/tracker.js"></script>
{/if}

{'js/bootstrap.min.js'|script}
{'js/jquery.nice-select.min.js'|script}
{'js/jquery.mCustomScrollbar.concat.min.js'|script}
{'js/lib/jquery.maskedinput.min.js'|script}
{'js/nouislider.min.js'|script}
{'js/swiper.js'|script}
{if $_modx->resource.class_key === 'msCategory'}
    {'js/components/msearch/default.js'|script}
{/if}
{'js/hoverIntent.min.js'|script}
{'js/async/autocomplete/jquery.autocomplete.js'|script}
{'js/async/autocomplete/city.js'|script}
{'js/async/autocomplete/search.js'|script}
{'js/script.js'|script}
{'js/apply_filters.js'|script}
{'js/cart.js'|script}
{'js/filters.js'|script}
{'js/mobile_menu.js'|script}
{'js/components/pdopage/custom.js'|script}
{'css/main.min.css'|css}

{*
{$_modx->regClientScript($assets_source~"js/bootstrap.min.js" ~ $assets_version)}
{$_modx->regClientScript($assets_source~"js/jquery.nice-select.min.js" ~ $assets_version)}
{$_modx->regClientScript($assets_source~"js/jquery.mCustomScrollbar.concat.min.js" ~ $assets_version)}
{$_modx->regClientScript($assets_source~"js/nouislider.min.js" ~ $assets_version)}
{$_modx->regClientScript($assets_source~"js/swiper.js" ~ $assets_version)}
{$_modx->regClientScript($assets_source~"js/script.js" ~ $assets_version)}
*}
{if !$mode_dev}
{'js/MindBox.js'|script}
{/if}
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

{'pdoCrumbs' | snippet : [
'showHome' => 1,
'tplWrapper' => '@INLINE <script class="microdata" type="application/ld+json">
{
 "@context": "http://schema.org",
 "@type": "BreadcrumbList",
 "itemListElement":
[ {$output} ]
}
</script>'
'tplHome' => '@INLINE {
   "@type": "ListItem",
   "position": {$idx},
   "item":
   {
    "@id": "{$link}",
    "name": "🌐 {$menutitle}"
    }
  },'
'tplCurrent' => '@INLINE {
   "@type": "ListItem",
   "position": {$idx},
   "item":
   {
    "@id": "{$link}",
    "name": "🔥 {$menutitle}"
    }
  }'
'tpl' => '@INLINE {
   "@type": "ListItem",
   "position": {$idx},
   "item":
   {
    "@id": "{$link}",
    "name": "🔸 {$menutitle}"
    }
  },'
]}
