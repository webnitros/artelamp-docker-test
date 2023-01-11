{set $bodyAttr == "class='page--account'"}
{extends 'file:templates/base.tpl'}
{block 'title'}{/block}
{block 'section'}
	<main>
		<section class="listing_title">
			<div class="jcont">
				<p class="title">Вход или регистрация</p>
			</div>
		</section>
		<section class="log-reg">
			<div class="jcont">

				<!-- Активную кнопку тоглим классом .active -->
				<div class="log-reg__controllers">
					<button data-target-id="formLogin">вход</button>
					<button class="active" data-target-id="formRegistration">регистрация</button>
				</div>

				<!-- Активную форму тоглим классом .active -->
				<div class="log-reg__form-list">
					<form id="formLogin" action="/" class="log-reg__form log-reg__form_log">
						<!-- Полю с ошибкой добавлять класс .error -->
						<label class="name error">
							<input type="text" name="name" placeholder="Логин (е-mail)">
							<span>Текст об ошибке</span>
						</label>
						<label class="phone">
							<input type="tel" name="phone" placeholder="Ваш телефон">
							<span>Текст об ошибке</span>
						</label>
						<label class="pass error">
							<input type="password" name="pass" placeholder="Ваш пароль">
							<em>Минимальная длина пароля должна быть не&nbsp;меньше 8 символовов.</em>
						</label>
						<mark>Нажимая кнопку «Войти», я подтверждаю свою дееспособность, даю согласие на обработку своих персональных данных в соответствии с <a href="#" target="_blank">Условиями</a></mark>
						<div class="log-reg__button log-reg__button_log">
							<button type="submit">войти</button>
							<a href="#">Забыли пароль?</a>
						</div>
						<div class="log-reg__form-footer">
							<p>Войти через социальную сеть</p>
							<a href="#" class="telegram" target="_blank"></a>
							<a href="#" class="vk" target="_blank"></a>
						</div>
					</form>
					<form id="formRegistration" action="/" class="log-reg__form log-reg__form_reg active">

						<ul class="log-reg__list">
							<li>Оперативная связь с персональным менеджером</li>
							<li>Оформление возврата и рекламации</li>
							<li>Вся необходимая документация, персональные условия и актуальные цены</li>
						</ul>

						<label class="phone">
							<input type="tel" name="phone" placeholder="Ваш телефон">
							<span>Текст об ошибке</span>
						</label>
						<label class="name">
							<input type="text" name="name" placeholder="Логин (е-mail)">
							<span>Текст об ошибке</span>
						</label>
						<label class="subscribe">
							<input type="checkbox" name="subscribe" checked>
							<span class="checker"></span>
							Подписаться на скидки и новости
						</label>
						<div class="log-reg__button">
							<button type="submit">создать аккаунт</button>
						</div>
						<div class="log-reg__form-footer">
							<p>Пароль от Вашего аккаунта будет выслан на указанный при регистрации почтовый ящик.</p>
						</div>
					</form>
				</div>
			</div>
		</section>
	</main>
{/block}