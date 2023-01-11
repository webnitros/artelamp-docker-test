<div class="plate">
    <div class="plate_menu">
        {var $menus = 2| menu_column}
        {foreach $menus as $resources}
            <div class="el">
                {'pdoMenu' | snippet : [
                    'element' => 'pdoMenu',
                    'cacheKey' => 'full_menu_catalog',
                    'cacheElementKey' => 'full_menu_catalog',
                    'countChildren' => 1,
                    'hereClass' => '',
                    'namespace' => 'header',
                    'cacheExpires' => 0,
                    'parents' => 2,
                    'resources' => $resources,
                    'level' => 1,
                    'where' => ['class_key:IN' => ['msCategory']],
                    'tpl' => '@FILE chunks/common/megamenu/level1/row.tpl',
                    'tplOuter' => '@INLINE [[+wrapper]]',
                    'outerClass' => 'unit',
                    'outerRow' => 'tit',
                ]}
            </div>
        {/foreach}
{*


        <div class="el flex-row-container">
            {'!pdoMenu' | snippet : [
                'element' => 'pdoMenu',
                'cacheKey' => 'full_menu_catalog',
                'cacheElementKey' => 'full_menu_catalog',
                'hereClass' => '',
                'namespace' => 'header',
                'cacheExpires' => 0,
                'parents' => 2,
                'level' => 1,
                'where' => ['class_key:IN' => ['msCategory']],
                'tpl' => '@FILE chunks/common/megamenu/level1/row.tpl',
                'tplOuter' => '@INLINE [[+wrapper]]',
                'outerClass' => 'unit',
                'outerRow' => 'tit',
            ]}
        </div>*}
    </div>
</div>