<a href="{$link}" style="text-decoration: none">
    <div class="el">
        <input type="radio" class="checkbox" name="{$filter_key}" id="mse2_{$table}{$delimeter}{$filter}_{$idx}" value="{$value}" {$checked} {$disabled}/>
        <label for="mse2_{$table}{$delimeter}{$filter}_{$idx}" class="{$disabled}">
        <span>
          {var $result = $title | strtrFenom : "Бра"}
            {$result}
        </span>
        </label>
    </div>
</a>