{if !empty($link) && !empty($pictures[0]) && !empty($pagetitle)}
	<item>
		<g:description>
            {if $description}
                {str_replace('&',' ',strip_tags($description))}
            {else}
                {str_replace('&',' ',strip_tags($pagetitle))}
            {/if}
		</g:description>
		<g:mpn>{$article}</g:mpn>
		<g:brand>Arte Lamp</g:brand>
		<g:image_link>
            {$pictures[0]}
		</g:image_link>
		<g:link>
            {$link}
		</g:link>
		<g:adult>no</g:adult>
		<g:id>{$id}</g:id>
		<g:condition>new</g:condition>
		<g:product_type>
            {$category} &gt; {$sub_category}
		</g:product_type>
		<g:title>
            {str_replace('&',' ',strip_tags($pagetitle))}
		</g:title>
		<g:availability>{if $stock > 0}in stock{else}out of stock{/if}</g:availability>
		<g:gtin>{$barcode}</g:gtin>
		<g:price>{$price} RUB</g:price>
		<g:google_product_category>{$google_category}</g:google_product_category>
        {foreach $pictures as $picture}
			<g:additional_image_link>
                {$picture}
			</g:additional_image_link>
        {/foreach}
		<g:custom_label_0>
            {$category} &gt; {$sub_category}
		</g:custom_label_0>
	</item>
{/if}