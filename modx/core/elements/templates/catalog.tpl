{extends 'file:templates/base.tpl'}
{block 'title'}

{/block}
{block 'crumbs'}
    {var $crumb = $_modx->getPlaceholder('_crumbs')}
    {if empty($crumb)}
        {include 'file:chunks/common/_crumbs.tpl'}
    {else}
        {$crumb}
    {/if}
{/block}
{block 'section'}


    {var $settings = 'fdk_msearch'|placeholder}


    {* настройки в mFilter2 core/components/sitedev/model/sitedev.class.php функция searchParams *}
    {var $results_mfilter = '!mFilter2' | snippet : $settings}
    <div id="mse2_mfilter">
        <section class="listing_title">
            <div class="jcont">
                <h1 class="title">
                    {$_modx->resource.pagetitle}
                    <span>
                    <i id="mse2_total">{'mse2_total'|placeholder}</i> <i id="mse2_total_text">{'mse2_total'|placeholder | declension : 'товар|товара|товаров'}</i>
                </span>
                </h1>
            </div>
        </section>
        {if stripos($_modx->resource.uri,"trekovyie-sistemyi") !== false}
            <section class="billboard-conf" style="background-color:#000000;">
                <div class="jcont">
                    <div class="billboard-conf__body">
                        <a target="_blank" href="https://config.artelamp.ru" class="billboard-conf__text" onclick="ym(49284802,'reachGoal','Config-Track')">
                            для расчета индивидуальной комплектации перейти в конфигуратор расчетов
                        </a>
                    </div>
                </div>
            </section>
        {/if}
        <section class="listing">
            <div class="jcont jcontnopad">
                <div class="dflex">
                    {$results_mfilter}
                </div>
            </div>
        </section>
    </div>



    <script class="mindbox">
        window.sendMindbox = function () {
            window.mindbox('async', {
                operation: 'Website.ViewCategory.ArteLamp',
                data: {
                    viewProductCategory: {
                        productCategory: {
                            ids: {
                                websiteArteLampRu: "{$_modx->resource.pagetitle|getIDByName}"
                            }
                        }
                    }
                }
            })
        }
    </script>
{/block}
