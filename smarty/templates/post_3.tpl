<h2>vista previa</h2>
Este es el contenido que se publicar&aacute; revise su ortograf&iacute;a y los enlaces y si est&aacute; todo correcto presione 
el bot&oacute;n {#post_submit_label#}

<hr />
<form method="post" action="{$smarty.server.PHP_SELF}">
<table width="460" border="0" cellspacing="0" cellpadding="2" >
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td class="votos">
	  <h3  id="vote_count_{$meme->ID}" style="text-align:center" align="center">
	  0
	  </h3>
	  <p>{#votes_label#}</p>
	   <div class="vote-class">
	<img src="styles/img/meme-votar.png" border="0" alt="{#votes_label#}" /></a>
	</div>
	</td>
  
    <td class="meme-content"><a href="{$url}"  alt="{$url}"  target="_blank" >{$title}<input type="hidden" name="url" value="{$url}" /><input type="hidden" name="title" value="{$title}" /></a>
	<div class="whowhen-class">{#posted_by_label#}&nbsp;<a class="whowhen-class" 
	style="font-size:10px" href="/user/{$logged_username}"><img src="{$gravatar}" border="0"alt="{$logged_username}"/><br/>{$logged_username}</a>
	&nbsp;{$meme->date_posted|date_format:$bf_date_posted}</div>
	{if $page_image}
	<a href="{$meme->url}" target="_new">	
	<img border="0" style="float:right;border:none" src="{$page_image}" alt="snapshot" />
	</a>
	{/if}
	{if $micro_content}
	<div style="padding:2px">{$micro_content}</div>
	{/if}
	  <p>
	  	  {$content_body|nl2br}
		  <input type="hidden" name="content_body" value="{$content_body}" />
	  </p>
	<div class="meme-footer-class">
	categor&iacute;a: {$category_name}<input type="hidden" name="category" value="{$category}" />&nbsp;|&nbsp
	{if $meme_tags}etiquetas: {$meme_tags} &nbsp;|&nbsp; {/if}<input type="hidden" name="meme_tags" value="{$meme_tags}" /> {if $meme_trackback}| <a href="{$meme_trackback}" alt="{$meme_trackback}">trackback</a><input type="hidden" name="meme_trackback" value="{$meme_trackback}" />&nbsp;{/if}{if $debates}|&nbsp;<b>permite debates</b>{/if}
	<input type="hidden" name="step" value="{$step}" /> 
	<input type="hidden" name="debates" value="{$debates}" />
	<input type="hidden" name="favicon" value="{$favicon}" />
	<input type="hidden" name="content_type" value="{$content_type}" />
	</div></td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
  <td></td><td >
<input class="view-button-class" type="submit" value="{#post_modify_submit_label#}" name="do_edit" />
<input class="view-button-class" type="submit" value="{#post_submit_label#}" name="do_post" />
</td>
</tr>  
</table>
</hr>
</form>
<div style="height:1400px;_height:1400px">&nbsp;</div>

