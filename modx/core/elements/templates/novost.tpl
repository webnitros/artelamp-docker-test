{extends 'file:templates/base.tpl'}
{block 'section'}
    {'css/index_page.css'|css}
    {'css/news.css'|css}
	<section class="news_single">
		<div class="jcont">
			<div class="news_single_wr">
				<div class="news_single_content">
					<a href="{$_modx->resource.introtext}" class='newspage_card_title'>{$_modx->resource.longtitle}</a>
					<div class='newspage_card_date'>{$_modx->resource.publihedon | date:'d-m-Y'}</div>
					<div class='newspage_card_text'>
                        {$_modx->resource.content}
					</div>
				</div>
				<div class="news_single_img">
					<img src="{$_modx->resource.news_img}" alt="">
				</div>
			</div>
		</div>
	</section>
	<section class="newspage pt140">
		<div class="jcont">
			<div class="newspage_elements rows" id="newsItems">
                {include 'file:chunks/newsList.tpl'}
			</div>

		</div>
	</section>
    {'js/news.js'|script}
{/block}