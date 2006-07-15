<form method="post" action="{$smarty.server.PHP_SELF}">
<div class="infobox">
<h3>{#post_title_some_advices#}</h3>
<div class="infoboxBody">
<br/>
<p>
{#post_criteria#}
</p>
<div style="padding-left:1em;padding-top:-1em">
<table border="0" cellpadding="2" cellspacing="0" width="300" align="center">
<tr><td>&nbsp;</td></tr>
<tr><td class="view-label-class"><font color="red">*</font> {#title_label#}</td></tr>
<tr><td><input class="view-input-class" value="{$title}" id="title" name="title" size="60" /></td></tr>
{if $error_title}<tr><td class="error">{#error_title#}</td></tr>{/if}
<tr><td class="view-label-class">{#url_label#}</td></tr>
<tr><td><input class="view-input-class" value="{$url}" id="url" name="url" size="60" /></td></tr>
{if $error_duplicate_url}<tr><td class="error">{#error_duplicate_url#}</td></tr>{/if}
{if $error_url}<tr><td class="error">{#error_url#}</td></tr>{/if}
<tr><td class="view-label-class">{#label_content_type#}</td></tr>
<tr><td>
<input type="radio" value="0" id="content_type_url" name="content_type" checked="true">{#meme_type_url#}</input>
<input type="radio" value="2" id="content_type_video" name="content_type">{#meme_type_video#}</input>
<input type="radio" value="1" id="content_type_text" name="content_type">{#meme_type_text#}</input>
</td></tr>
<tr><td class="view-label-class" valign="middle">{#label_allows_debates#}&nbsp;<input class="view-input-class" type="checkbox" value="1" id="debates" name="debates" /></td></tr>
<tr><td><input type="hidden" id="step" name="step" value="{$step}" /><input class="view-button-class" type="submit" value="{#post_submit_label#}" id="do_post" name="do_post" />
<tr><td><br/><font size="x-small">{#video_link_explanation#}</td></tr>
</table>
</div>
<div style="height:250px">
</div>
</div>
<div class="infoboxFooter"><p>&nbsp;</p></div>
</div>
</form>
<script type="text/javascript">
document.getElementById('title').focus();
</script>
<div style="height:1400px;_height:1400px">&nbsp;</div>
