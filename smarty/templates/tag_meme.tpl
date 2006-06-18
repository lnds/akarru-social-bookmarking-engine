{include file="meme_layout.tpl"}
<h3>Etiquetas</h3>
{html_table loop=$meme_tags table_attr='border="0" cellspacing="4" cellpadding="4" ' cols="6"}
{if $logged_in}
{include file="form_header.tpl"}
<table>
<tr><td class="view-label-class">{#tag_label#}</td></tr>
<tr><td><input type="text" name="tags" class="view-input-class" size="60" /></td></tr>
<tr><td><input type="hidden" name="meme_id" value="{$meme_id}" /><input class="view-button-class" type="submit" name="{#tag_submit_label#}" value="{#tag_submit_label#}" /></td></tr>
</table>
{include file="form_footer.tpl"}
{/if}
<div style="height:1400px;_height:1400px">&nbsp;</div>

