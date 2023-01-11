<div class="unit filter_category">
    {if !$title}
        {if $_modx->resource.id != 2 && $_modx->resource.parent == 2}
            {var $title = $_modx->resource.pagetitle}
        {else}
            {if $_modx->resource.parent != 2 && $_modx->resource.id != 2}
                {var $title = $_modx->resource.parent | pdofield :  'pagetitle'}
            {/if}
        {/if}
    {else}
        {if $_modx->resource.id != 2 && $_modx->resource.parent != 2}
            {var $title = $_modx->resource.pagetitle}
        {/if}
    {/if}
    <button class="btn_opener js_btn">{$title ? $title : 'Тип светильников'}</button>
    <div class="the_list">
        {$output}
    </div>
</div>