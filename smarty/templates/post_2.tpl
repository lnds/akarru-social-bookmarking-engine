{include file="form_header.tpl"}
<div style="max-width:200">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr><td>&nbsp;</td></tr>
<tr><td class="view-label-class">{#title_label#}</td></tr>
<tr><td class="view-data-class">{$title}<input type="hidden" name="title" value="{$title}" /></td></tr>
<tr><td class="view-label-class">{#url_label#}<input type="hidden" name="url" value="{$url}" /></td></tr>
<tr><td class="view-data-class">{$url|wordwrap:45:"<br />\n":true}</td></tr>
<tr><td class="view-label-class"><font color="red">*</font>{#content_label#}</td></tr>
{if $error_content_body}<tr><td class="error">{#error_content_body#}</td></tr>{/if}
<tr><td><textarea name="content_body" class="view-input-class" cols="45" rows="4">{$content_body}</textarea></td></tr>
<tr><td class="view-label-class"><font color="red">*</font>{#category_label#}</td></tr>
<tr><td><select name="category" class="view-input-class">{html_options options=$cats selected=$category}</select></td></tr>
{if $error_category}<tr><td class="error">{#error_category#}</td></tr>{/if}
<tr><td class="view-label-class">{#tags_label#}</td></tr>
<tr><td><input class="view-input-class" value="{$meme_tags}" name="meme_tags" size="60" /></td></tr>
<tr><td class="view-label-class">{#trackback_label#}</td></tr>
<tr><td><input class="view-input-class" value="{$meme_trackback}" name="meme_trackback" size="60" /></td></tr>
{if $error_trackback}<tr><td class="error">{#error_trackback#}</td></tr>{/if}
<tr><td><input type="hidden" name="step" value="{$step}" />
<input class="view-button-class" type="submit" value="{#post_submit_label#}" name="do_post" />
</td></tr>
</table>
</div>
{include file="form_footer.tpl"}
<div style="height:400px">&nbsp;</div>

