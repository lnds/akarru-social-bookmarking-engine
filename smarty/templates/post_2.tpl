{include file="form_header.tpl"}
<div style="max-width:200">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr><td>&nbsp;</td></tr>
{if $error_meme}<tr><td class="error">{#error_meme#}</td></tr>{/if}
<tr><td class="view-label-class">{#title_label#}</td></tr>
<tr><td class="view-data-class">{$title}<input type="hidden" id="title" name="title" value="{$title}" /></td></tr>
{if $url}
<tr><td class="view-label-class">{#url_label#}<input type="hidden" id="url" name="url" value="{$url}" /></td></tr>
<tr><td class="view-data-class">{$url|wordwrap:45:"<br />\n":true}</td></tr>
{/if}
<tr><td class="view-label-class"><font color="red">*</font>{#content_label#}</td></tr>
{if $error_content_body}<tr><td class="error">{#error_content_body#}</td></tr>{/if}
<tr><td><textarea id="content_body" name="content_body" class="view-input-class" cols="45" rows="4">{$content_body}</textarea></td></tr>
<tr><td class="view-label-class"><font color="red">*</font>{#category_label#}</td></tr>
<tr><td><select id="category" name="category" class="view-input-class">{html_options options=$cats selected=$category}</select></td></tr>
{if $error_category}<tr><td class="error">{#error_category#}</td></tr>{/if}
<tr><td class="view-label-class">{#tags_label#}</td></tr>
<tr><td><input class="view-input-class" value="{$meme_tags}" id="meme_tags" name="meme_tags" size="60" /></td></tr>
<tr><td class="view-label-class">{#trackback_label#}</td></tr>
<tr><td><input class="view-input-class" value="{$meme_trackback}" id="meme_trackback" name="meme_trackback" size="60" /></td></tr>
{if $error_trackback}<tr><td class="error">{#error_trackback#}</td></tr>{/if}
<tr><td><input type="hidden" id="content_type" name="content_type" value="{$content_type}" />
<input type="hidden" id="debates" name="debates" value="{$debates}" /><input type="hidden" id="favicon" name="favicon" value="{$favicon}" /><input type="hidden" id="step" name="step" value="{$step}" />
<input class="view-button-class" type="submit" value="{#post_submit_label#}" id="do_post" name="do_post" />
</td></tr>
</table>
</div>
{include file="form_footer.tpl"}
<script type="text/javascript">
document.getElementById('content_body').focus();
</script>
<div style="height:1400px;_height:1400px">&nbsp;</div>

