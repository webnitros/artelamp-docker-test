{if $value == '1'}
        <input type="checkbox" class="checkbox" name="[[+filter_key]]" id="mse2_[[+table]][[+delimeter]][[+filter]]_[[+idx]]" value="[[+value]]" [[+checked]] [[+disabled]]/>
        <label for="mse2_[[+table]][[+delimeter]][[+filter]]_[[+idx]]" class="[[+disabled]] ">
            <span>
                {if $filter == 'sale'}
                    <img src="/inc/images/list_sale.png" alt="">
                {/if}
                {if $filter == 'new'}
                    <img src="/inc/images/list_new.png" alt="">
                {/if}
                [[%mse2_filter_[[+filter]]_value]] <i>{$num}</i>
            </span>

        </label>
{else}
    <div></div>
{/if}