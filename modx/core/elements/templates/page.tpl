{extends 'file:templates/base.tpl'}
{block 'section_title'}
    <section class="textpage">
        <div class="jcont">
            <h1>{if $_modx->resource.longtitle}{$_modx->resource.longtitle}{else}{$_modx->resource.pagetitle}{/if}</h1>
            [[*content]]
        </div>
    </section>
{/block}
