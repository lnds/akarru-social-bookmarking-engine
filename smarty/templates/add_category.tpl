<p>
{#category_add_instructions#}
</p>
{include file="form_header.tpl"}
<table width="360" border="0">
{if $error_category}<tr><td class="error">{#error_category#}</td></tr>{/if}
<tr><td class="view-label-class"><b>{#category_name_label#}</b></td></tr>
<tr><td><input type="text" name="cat_name" value="{$cat_name}" class="view-input-class" size="60" /></td></tr>
{if $error_category_name_exists }<tr><td class="error">{#error_category_name_exists#}</td></tr>{/if}
{if $error_category_name }<tr><td class="error">{#error_category_name#}</td></tr>{/if}
<tr><td class="view-label-class"><b>{#category_description_label#}</b></td></tr>
<tr><td><input type="text" name="cat_description" value="{$cat_description}" class="view-input-class" size="60" /></td></tr>
<tr><td><input type="submit" name="do_category_add" value="{#label_category_add#}" /></td></tr>

</table>
{include file="form_footer.tpl"}