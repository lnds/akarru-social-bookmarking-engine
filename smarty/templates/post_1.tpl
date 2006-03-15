<form method="post" action="{$smarty.server.PHP_SELF}">
<div class="infobox">
<h3>al publicar considera lo siguiente:</h3>
<div class="infoboxBody">
<br/>
<p>
{#post_criteria#}
</p>
<div style="padding-left:1em;padding-top:-1em">
<table border="0" cellpadding="2" cellspacing="0" width="300" align="center">
<tr><td>&nbsp;</td></tr>
<tr><td class="view-label-class"><font color="red">*</font> {#title_label#}</td></tr>
<tr><td><input class="view-input-class" value="{$title}" name="title" size="60" /></td></tr>
{if $error_title}<tr><td class="error">{#error_title#}</td></tr>{/if}
<tr><td class="view-label-class">{#url_label#}</td></tr>
<tr><td><input class="view-input-class" value="{$url}" name="url" size="60" /></td></tr>
{if $error_duplicate_url}<tr><td class="error">{#error_duplicate_url#}</td></tr>{/if}
{if $error_url}<tr><td class="error">{#error_url#}</td></tr>{/if}
<tr><td class="view-label-class">{#label_content_type#}</td></tr>
<tr><td>
	<select class="view-input-class" name="content_type">
		<option value="0" selected="selected">url</option>
		<option value="2">youtube</option>
	</select>
</td></tr>
<tr><td><input type="hidden" name="step" value="{$step}" /><input class="view-button-class" type="submit" value="{#post_submit_label#}" name="do_post" />
</table>
</div>
<div style="height:250px">
</div>
</div>
<div class="infoboxFooter"><p>&nbsp;</p></div>
</div>
</form>
<div style="height:400px">&nbsp;</div>
