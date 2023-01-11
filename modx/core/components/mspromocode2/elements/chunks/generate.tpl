{if $closed is empty}
    <div class="mspc2-generate [ js-mspc2-generate ]"
         data-propkey="{$propkey}"
         data-seconds="{$seconds}"
    >
        {if $coupon?}
            <button class="mspc2-generate__close [ js-mspc2-generate-close ]" type="button">
                <svg class="mspc2-generate__close-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z"></path></svg>
            </button>

            <div class="mspc2-generate__discount">
                {$coupon['discount']}
                {($coupon['discount'] | preg_match : '~%~')
                    ? '' : ('ms2_frontend_currency' | lexicon)}
            </div>
            <div class="mspc2-generate__title">
                Только сейчас скидка на Ваш заказ!
            </div>

            <button class="mspc2-generate__code [ js-mspc2-generate-clipboard ]"
                    data-clipboard-text="{$coupon['code']}"
                    type="button">
                <span class="mspc2-generate__code-label">
                    Промокод:
                </span>
                <span class="mspc2-generate__code-value [ js-mspc2-generate-code ]">
                    {$coupon['code']}
                </span>
                <span class="mspc2-generate__code-copied">
                    Скопировано!
                </span>
            </button>

            <div class="mspc2-generate__clock [ js-mspc2-generate-clock ]" style="display: none;">
                <div class="mspc2-generate__clock-label">
                    Истекает:
                </div>
                <div class="mspc2-generate__clock-numbers">
                    <div class="mspc2-generate__clock-number mspc2-generate__clock-number_hours [ js-mspc2-generate-clock-hours ]" style="display: none;">
                        00
                    </div>
                    <div class="mspc2-generate__clock-number mspc2-generate__clock-number_minutes [ js-mspc2-generate-clock-minutes ]">
                        00
                    </div>
                    <div class="mspc2-generate__clock-number mspc2-generate__clock-number_seconds [ js-mspc2-generate-clock-seconds ]">
                        00
                    </div>
                </div>
            </div>
        {/if}
    </div>
{/if}