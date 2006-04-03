{include file="form_header.tpl"}                        
<div style="max-width:200">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr><td>&nbsp;</td></tr>
<tr><td class="view-label-class">{#title_label#}</td></tr>
<tr><td><input class="view-input-class" type="text" name="title" value="{$meme->title}" size="60" /></td></tr>
<tr><td class="view-label-class">{#url_label#}</td></tr>
<tr><td><input type="hidden" name="meme_id" value="{$meme->ID}" /><input class="view-input-class" type="text" name="url" value="{$meme->url}" size="60"  /></td></tr>
<tr><td class="view-label-class"><font color="red">*</font>{#content_label#}</td></tr>
{if $error_content_body}<tr><td class="error">{#error_content_body#}</td></tr>{/if}
<tr><td><textarea name="content_body" class="view-input-class" cols="45" rows="8">{$meme->content}</textarea></td></tr>
<tr><td class="view-label-class"><font color="red">*</font>{#category_label#}</td></tr>
<tr><td><select name="category" class="view-input-class">{html_options options=$cats selected=$meme->category}</select></td></tr>
{if $error_category}<tr><td class="error">{#error_category#}</td></tr>{/if}
<tr><td class="view-label-class">{#tags_label#}</td></tr>
<tr><td><input class="view-input-class" type="text" value="{$meme_tags}" name="meme_tags" size="60" /></td></tr>
<tr><td class="view-label-class">{#label_content_type#}</td></tr>
<tr><td>
	<select class="view-input-class" name="content_type">
		<option value="0" selected="selected">url</option>
		<option value="2">youtube</option>
		<option value="1">texto</option>
	</select>
</td></tr>
<tr><td>
{#label_allows_debates#}&nbsp;<input type="checkbox" name="debates" value="1" {if $meme->allows_debates}checked="checked"{/if} />
</td></tr>
<tr><td>
<input class="view-button-class" type="submit" value="{#post_modify_submit_label#}" name="do_post" />
</td></tr>
</table>
</div>
{include file="form_footer.tpl"}
<div style="height:1400px;_height:1400px">&nbsp;</div>
