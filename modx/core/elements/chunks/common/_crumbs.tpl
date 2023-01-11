<section class="breadcrumbs">
	<div class="jcont">
        {$modx->runSnippet('pdoCrumbs', [
        'showAtHome' => 0,
        'showHome' => 1,
        'outerClass' => 'nav nav-pills',
        'tplWrapper' => '@INLINE <div itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumbs_wrap">{$output}</div>',


        'tpl' => '@INLINE 			<span style="padding:0;margin:0;" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" ><a itemprop="item" href="{$link}">{$menutitle}<meta itemprop="name" content="{$menutitle}"/></a> <meta itemprop="position" content="{$idx}"/></span>',
        'tplCurrent' => '@INLINE 	<span style="padding:0;margin:0;" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" ><span itemprop="item">{$menutitle}<meta itemprop="name" content="{$menutitle}"/></span> <meta itemprop="position" content="{$idx}"/></span>',
        'tplHome' => '@INLINE 		<span style="padding:0;margin:0;" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" ><a itemprop="item" href="{$link}">{$menutitle}<meta itemprop="name" content="{$menutitle}"/></a> <meta itemprop="position" content="{$idx}"/></span>',
        ])}

	</div>
</section>