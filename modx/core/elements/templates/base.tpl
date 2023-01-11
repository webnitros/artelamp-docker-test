{var $assets_source = '/inc/'}
<!DOCTYPE html>
<html lang="en">
<head>
    {include 'file:chunks/common/_head.tpl'}
    {block 'head'}{/block}
</head>
<body {$bodyAttr}>
{'UserLocation.initialize'|snippet}
{block 'header'}
    {include 'file:chunks/common/_header.tpl'}
{/block}
{block 'crumbs'}
    {include 'file:chunks/common/_crumbs.tpl'}
{/block}
{block 'section_title'}
    {block 'title'}
        <section class="listing_title">
            <div class="jcont">
                <h1 class="title">{$modx->resource->pagetitle}</h1>
            </div>
        </section>
    {/block}
    {block 'section'}
        <section>
            <div class="jcont">
                {block 'main'}
                    {$modx->resource->content}
                {/block}
            </div>
        </section>
    {/block}
{/block}
{include 'file:chunks/common/_footer.tpl'}
{include 'file:chunks/location/modal.tpl'}
{include 'file:chunks/common/_modal.tpl'}
{include 'file:chunks/model/one_click.tpl'}
{block 'bottomJs'}{/block}
{block 'modals'}{/block}
{block 'counters'}

    {var $mode_dev = 'mode_dev'|placeholder}

    {if !$mode_dev}
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script src="//code-ya.jivosite.com/widget/S3ZFuKZbVO" async></script>
        <!-- Facebook Pixel Code -->
    {/if}
    {ignore}
        {*    <script>*}
        {*		!function(f,b,e,v,n,t,s)*}
        {*                { if(f.fbq)return;n=f.fbq=function(){ n.callMethod?*}
        {*                n.callMethod.apply(n,arguments):n.queue.push(arguments)};*}
        {*                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';*}
        {*                n.queue=[];t=b.createElement(e);t.async=!0;*}
        {*                t.src=v;s=b.getElementsByTagName(e)[0];*}
        {*                s.parentNode.insertBefore(t,s)}(window, document,'script',*}
        {*			'https://connect.facebook.net/en_US/fbevents.js');*}
        {*		fbq('init', '369178684577453');*}
        {*		fbq('track', 'PageView');*}
        {*    </script>*}
        {*    <noscript><img height="1" width="1" style="display:none"*}
        {*                   src="https://www.facebook.com/tr?id=369178684577453&ev=PageView&noscript=1"*}
        {*        /></noscript>*}
    {/ignore}
    <!-- End Facebook Pixel Code -->
{/block}
</body>
</html>
