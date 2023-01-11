{*<div class="unit"> - меню на флексе*}
<div class="unit flex-row-item">
    {var $outer = '!pdoMenu' | snippet : [
        'element' => 'pdoMenu',
        'cacheKey' => 'full_menu_catalog',
        'cacheElementKey' => 'full_menu_catalog',
        'hereClass' => 'active',
        'namespace' => 'header',
        'cacheExpires' => 0,
        'parents' => $id,
        'level' => 1,
        'where' => ['class_key:IN' => ['msCategory']],
        'tpl' => '@INLINE <li><a [[+classes]] href="[[+link]]">[[+menutitle]]</a>[[+wrapper]]</li>',
        'tplOuter' => '@INLINE <ul [[+classes]]>[[+wrapper]]</ul>',
        'outerClass' => 'submenu',
    ]}
    <p class="tit{$outer? ' me_submenu':''}">
        <a href="{$link}" class="tit_name" {$attributes}>
            {$menutitle}
        </a>
        <a href="{$link}?new=1"></a>
        <a href="{$link}">Все</a>
    </p>
    {$outer}
</div>