<section class="breadcrumbs">
    <div class="jcont">
        {$modx->runSnippet('pdoCrumbs', [
            'showAtHome' => 0,
            'showHome' => 1,
            'outerClass' => 'nav nav-pills',
            'tpl' => '@INLINE <a href="{$link}">{$menutitle}</a>',
            'tplCurrent' => '@INLINE <span>[[+menutitle]]</span>',
            'tplWrapper' => '@INLINE <div class="breadcrumbs_wrap">{$output}</ol>',
        ])}

    </div>
</section>