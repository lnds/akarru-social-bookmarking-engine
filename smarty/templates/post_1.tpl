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
<input type="radio" value="0" id="content_type" name="content_type" checked="true">url</input>
<input type="radio" value="2" id="content_type" name="content_type">video</input>
<input type="radio" value="1" id="content_type" name="content_type">texto</input>
</td></tr>
<tr><td class="view-label-class" valign="middle">{#label_allows_debates#}&nbsp;<input class="view-input-class" type="checkbox" value="1" name="debates" /></td></tr>
<tr><td><input type="hidden" name="step" value="{$step}" /><input class="view-button-class" type="submit" value="{#post_submit_label#}" name="do_post" />
<tr><td><br/><font size="x-small">(Si envias video este debe ser un link a un video <a href="http://www.youtube.com">youtube</a>, <a href="http://video.google.com">google video</a>, o <a href="http://www.myvideo.es">MyVideo.es</a>)</a></td></tr>

</table>
</div>
<div style="height:250px">
</div>
</div>
<div class="infoboxFooter"><p>&nbsp;</p></div>
</div>
</form>
<div style="height:1400px;_height:1400px">&nbsp;</div>
