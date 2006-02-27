<div class="meme-bg">
<div style="padding-left:1em">
<table width="420" border="0" cellspacing="0" cellpadding="2" >
  <tr>
    <td colspan="2">
    <div class="meme-content"><br/><a href="{$meme->url}"  onclick="goto_url({$meme->ID})">{$meme->title}</a><br />
    <p>
    <strong>Esta es la circulaci&oacute;n de esta noticia durante el &uacute;ltimo mes por la blogosfera:</strong>
<table>
{if $views}
<tr><th align="left">agregador</th><th>vistas</th><th>clicks</th><th>circulaci&oacute;n</th></tr>
{foreach from=$views item=item}
<tr><td>&nbsp;<a href="http://{$item->host}">{$item->host}</a>&nbsp;</td>
<td align="right">&nbsp;{$item->views}&nbsp;</td><td align="right">&nbsp;{$item->clicks}&nbsp;</td>
<td align="right">&nbsp;{$item->circulation}&nbsp;</td>
</tr>
{foreachelse}
<tr><td colspan="2"><strong>No hay informaci&oacute;n de circulaci&oacute;n disponible para esta noticia, consulta en 24 horas</strong></td></tr>
{/foreach}
<tr><td>&nbsp;</td>
<td align="right">&nbsp;<b>{$total->views}</b>&nbsp;</td>
<td align="right">&nbsp;<b>{$total->clicks}</b>&nbsp;</td>
<td align="right">&nbsp;<b>{$total->circulation}</b>&nbsp;</td>
</tr>
{else}
<tr><td colspan="2"><strong>No hay informaci&oacute;n de circulaci&oacute;n disponible para esta noticia, consulta en 24 horas</strong></td></tr>
{/if}
</table>
</p>
<p>
La circulaci&oacute;n es calculada revisando las <a href="http://www.feedburner.com/fb/a/api/awareness">estad&iacute;sticas</a> provistas por <a href="http://www.feedburner.com/">FeedBurner</a>.
</p>
     </div>
     </td></tr>
  <tr><td colspan="2">
  <hr />
	<div class="whowhen-class">{#posted_by_label#}&nbsp;<a class="whowhen-class" 
	style="font-size:10px" href="profile.php?user_name={$meme->username}">{$meme->username}</a>
	&nbsp;{$meme->date_posted|date_format:$bf_date_posted}</div>
	  <p>
  
	  <p>
	  	  {$meme->content|nl2br}
	  </p>
  </td></tr>
  <tr><td colspan="2">&nbsp;&nbsp;<div style="padding-left:2em" class="meme-footer-class"><a href="comment.php?meme_id={$meme->ID}">{#comments_label#} {$meme->comment_count}</a> | <a href="tag_meme.php?meme_id={$meme->ID}">{#tag_meme_label#}</a> | <a href="{$meme->url}"  onclick="goto_url({$meme->ID})">{#url_label#}</a> | {#cat_label#} : <a href="show_cat.php?cat_id={$meme->cat_id}">{$meme->cat_title}</a> | {$meme->clicks}/{$meme->rank}</div></td>
  </td></tr>
</table>
</p>
</div>
<div class="infoboxFooter"><p>&nbsp;</p></div>
</div>

<hr />
