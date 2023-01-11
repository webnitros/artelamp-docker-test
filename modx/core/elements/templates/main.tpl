{extends 'file:templates/base.tpl'}
{block 'head'}
    <link href="https://artelamp.ru/" rel="alternate" hreflang="ru-RU" />
    <link href="https://artelamp.it/" rel="alternate" hreflang="en-IT" />
    {'css/animate.min.css'|css}
    {'js/index.js'|script}
{/block}
{block 'crumbs'}
{/block}
{block 'section_title'}
    {* сладерый на html*}
    {include 'file:chunks/index/slider.tpl'}
    <section class="new_slider">
        <div class="jcont">
            <div class="new_slider_title">
                <p>Новинки</p>
            </div>
            <div class="new_slider_swiper">
                <div class="swiper-wrapper">
                    {'@FILE snippets/mainPage/newSlider.php'|snippet:[
                        'assets_source'=> $assets_source
                    ]}
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>
    <section class="main_brand">
        <div class="jcont">
            [[*content]]
        </div>
    </section>
{/block}
{block 'bottomJs'}
    <script src="{$assets_source}js/wow.js"></script>
    {ignore}
        <script>
            var t = new WOW().init()
            $('img[data-video]').on('click', function() {

                var height = $(this).height()
                var imgSrc = $(this).attr('src')
                var width = $(this).width()
                var src = $(this).data('video')
                var frame = $(`<video src="${src}" style="max-width: 100%; width: ${width}px;height:${height}px" controls loop poster="${imgSrc}" frameborder="0" allow="accelerometer; autoplay; encrypted-media;" allowfullscreen autoplay></iframe>`).appendTo($(this).parent())
                $(this).fadeOut(0)
            })
        </script>
    {/ignore}
{/block}