{include file="form_header.tpl"}                        
<div style="max-width:200">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr><td>&nbsp;</td></tr>
<tr><td class="view-label-class">{#title_label#}</td></tr>
<tr><td><input class="view-input-class" type="text" id="title" name="title" value="{$meme->title}" size="60" /></td></tr>
<tr><td class="view-label-class">{#url_label#}</td></tr>
<tr><td><input type="hidden" id="meme_id" name="meme_id" value="{$meme->ID}" /><input class="view-input-class" type="text" id="url" name="url" value="{$meme->url}" size="60"  /></td></tr>
<tr><td class="view-label-class"><font color="red">*</font>{#content_label#}</td></tr>
{if $error_content_body}<tr><td class="error">{#error_content_body#}</td></tr>{/if}
<tr><td><textarea id="content_body" name="content_body" class="view-input-class" cols="45" rows="8">{$meme->content}</textarea></td></tr>
<tr><td class="view-label-class"><font color="red">*</font>{#category_label#}</td></tr>
<tr><td><select id="category" name="category" class="view-input-class">{html_options options=$cats selected=$meme->category}</select></td></tr>
{if $error_category}<tr><td class="error">{#error_category#}</td></tr>{/if}
<tr><td class="view-label-class">{#tags_label#}</td></tr>
<tr><td><input class="view-input-class" type="text" value="{$meme_tags}" id="meme_tags" name="meme_tags" size="60" /></td></tr>
<tr><td class="view-label-class">{#label_content_type#}</td></tr>
<tr><td>
<input type="radio" value="0" id="content_type" name="content_type" {if $meme_type == 0}checked="true"{/if}>{#meme_type_url#}</input>
<input type="radio" value="2" id="content_type" name="content_type" {if $meme_type == 2}checked="true"{/if}>{#meme_type_video#}</input>
<input type="radio" value="1" id="content_type" name="content_type" {if $meme_type == 1}checked="true"{/if}>{#meme_type_text#}</input>
</td></tr>
<tr><td>
{#label_allows_debates#}&nbsp;<input type="checkbox" id="debates" name="debates" value="1" {if $meme->allows_debates}checked="checked"{/if} />
</td></tr>
{if $is_admin}
<tr><td>
{#label_disable#}&nbsp;<input type="checkbox" id="disable" name="disable" value="1" {if $meme->disabled}checked="checked"{/if} />
</td></tr>
{/if}

<tr><td>
<input class="view-button-class" type="submit" value="{#post_modify_submit_label#}" id="do_post" name="do_post" />
</td></tr>
</table>
</div>
{include file="form_footer.tpl"}
<script type="text/javascript">
document.getElementById('title').focus();
</script>
<div style="height:1400px;_height:1400px">&nbsp;</div>
