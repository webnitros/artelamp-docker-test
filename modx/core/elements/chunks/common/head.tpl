{var $assets_source = '/inc/'}
{var $assets_version = '?v='~'assets_version' | option}
<meta charset="{$modx->config.modx_charset}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<base href="{$modx->config.site_url}" />
<title>{$modx->runSnippet("pdoTitle")} / {$modx->config.site_name}</title>

<link type="image/x-icon" rel="shortcut icon" href="/inc/images/favicon.ico">


<link rel="stylesheet" href="{$assets_source}css/fonts.css">
<link rel="stylesheet" href="{$assets_source}css/bootstrap.min.css">
<link rel="stylesheet" href="{$assets_source}css/reset.css">
<link rel="stylesheet" href="{$assets_source}css/nice-select.css">
<link rel="stylesheet" href="{$assets_source}css/jquery.mCustomScrollbar.css" />
<link rel="stylesheet" href="{$assets_source}css/nouislider.css">
<link rel="stylesheet" href="{$assets_source}css/style.css">
<link rel="stylesheet" href="{$assets_source}css/swiper.css">
<link rel="stylesheet" href="{$assets_source}css/responsive.css">


{$_modx->regClientStartupScript($assets_source~"js/jquery-1.11.3.min.js" ~ $assets_version)}

{'js/bootstrap.min.js'|script}
{'js/jquery.nice-select.min.js'|script}
{'js/jquery.mCustomScrollbar.concat.min.js'|script}
{'js/nouislider.min.js'|script}
{'js/swiper.js'|script}
{'js/script.js'|script}

{*

{$_modx->regClientScript($assets_source~"js/bootstrap.min.js" ~ $assets_version)}
{$_modx->regClientScript($assets_source~"js/jquery.nice-select.min.js" ~ $assets_version)}
{$_modx->regClientScript($assets_source~"js/jquery.mCustomScrollbar.concat.min.js" ~ $assets_version)}
{$_modx->regClientScript($assets_source~"js/nouislider.min.js" ~ $assets_version)}
{$_modx->regClientScript($assets_source~"js/swiper.js" ~ $assets_version)}
{$_modx->regClientScript($assets_source~"js/script.js" ~ $assets_version)}
*}


<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
