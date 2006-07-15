{include file="meme_layout.tpl"}
<h3>{#tag_label#}</h3>
{html_table loop=$meme_tags table_attr='border="0" cellspacing="4" cellpadding="4" ' cols="6"}
{if $logged_and_valid}
{include file="form_header.tpl"}
<table>
<tr><td class="view-label-class">{#tag_label#}</td></tr>
<tr><td><input type="text" id="tags" name="tags" class="view-input-class" size="60" /></td></tr>
<tr><td><input type="hidden" id="meme_id" name="meme_id" value="{$meme_id}" /><input class="view-button-class" type="submit" id="{#tag_submit_label#}" name="{#tag_submit_label#}" value="{#tag_submit_label#}" /></td></tr>
</table>
{include file="form_footer.tpl"}
<script type="text/javascript">
document.getElementById('tags').focus();
</script>
{/if}
<div style="height:1400px;_height:1400px">&nbsp;</div>

