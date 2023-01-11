{extends 'file:templates/base.tpl'}
{block 'section'}
    {'css/index_page.css'|css}
    {'css/news.css'|css}
    <section class="newspage">
        <div class="jcont">
            <div class="newspage_elements" id="newsItems">
                {include 'file:chunks/newsList.tpl'}
            </div>
        </div>
    </section>
    {'js/news.js'|script}
{/block}