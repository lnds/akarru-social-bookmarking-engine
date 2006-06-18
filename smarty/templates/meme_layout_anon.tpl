{capture name=layout}
<div class="meme-bg">
<table width="440" border="0" cellspacing="0" cellpadding="2" >
  <tr><td>
  <div style="padding-left:1em" class="meme-footer-class"> 
  {if $meme->prior_meme}
  &lt;&nbsp;<a href="{$meme->prior_meme->permalink}">{$meme->prior_meme->title}</a>
  {/if}
  </div>
  </td>
  <td>&nbsp;&nbsp;</td>
  <td>
  <div style="padding-right:1em" class="meme-footer-class"> 
  {if $meme->next_meme}
  <a href="{$meme->next_meme->permalink}">{$meme->next_meme->title}</a>&nbsp;&gt;
  {/if}
  </div>
  </td></tr>
  <tr>
  <td colspan="3">
    <div class="votos" >
    <a href="{$meme->permalink}">
	  <h3  id="vote_count_{$meme->ID}" style="text-align:center" align="center">
	  {$meme->votes}
	  </h3>
	  </a>
    </div>
    <div class="meme-content"><br/>
    <a href="/click/{$meme->ID}/{$meme->url}" >{$meme->title}</a>
	<div class="whowhen-class">
	&nbsp;{#cat_label#}: <a style="font-size:10px" href="/show_cat.php?cat_id={$meme->cat_id}">{$meme->cat_title}</a>
	&nbsp;{$meme->date_posted|date_format:$bf_date_posted}  
	&nbsp;{#posted_by_label#}&nbsp;
	<a href="/user/{$meme->username}"><img src="{$meme->small_gravatar}" alt="{$meme->username}" border="0"/></a><br/>
	<a style="font-size:10px" href="/user/{$meme->username}">{$meme->username}</a>
	</div>
	<p style="right-margin:1em">
	{if $meme->page_image}
	<a  href="/click/{$meme->ID}/{$meme->url}" target="_new">	
	<img border="0" style="float:right;border:none" src="{$meme->page_image}" alt="snapshot" />
	</a>
	{/if}
	{if $meme->micro_content}
	<span style="padding:4px">{$meme->micro_content}</span><br/>
	<a href="/videos.php">ver m&aacute;s videos</a><br/>
	{/if}
	  	  {$meme->content|nl2br}
		  <br/>
	{if $meme->allows_debates}
	  <a  href="{$meme->permalink}#debate">{#debate_label#} {#friend_posture#}: {$meme->debate_pos}, {#foe_posture#}: {$meme->debate_neg}</a>
	{else}
 	  <a  href="{$meme->permalink}">{#comments_label#} {$meme->comments}</a>	
	{/if}
	  </p>
    </div>
     </td></tr>
     {if $tags_of_meme}
     <tr><td colspan="3"><b>Etiquetas:</b>
	{html_table loop=$tags_of_meme table_attr='border="0" cellspacing="4" cellpadding="4" ' cols="6"}
	</td></tr>
     {/if}
  <tr><td colspan="3"><div style="padding-left:1em" class="meme-footer-class">
<a href="/tag_meme.php?meme_id={$meme->ID}">{#tag_meme_label#}</a> | {#cat_label#}: <a href="/cat/{$meme->cat_title}">{$meme->cat_title}</a> | {#url_label#}&nbsp;<a href="/click/{$meme->ID}/{$meme->url}" onclick="goto_url({$meme->ID})">{$meme->url|truncate:30:"..."}</a>&nbsp;</div> 
  </td></tr>
  <tr><td colspan="3">
  <div style="padding-left:1em" class="meme-footer-class">
  Compartir: <a href="sendlink.php?meme_id={$meme->ID}"><img src="/sbs/email.png" alt="enviar por correo" border="0" /></a>
  &nbsp;<a onclick="social_click({$meme->ID})" href="http://del.icio.us/post?title={$meme->sociable_title}&url={$meme->sociable_link}"><img src="/sbs/delicious.png" border="0" alt="del.icio.us"/></a>
  &nbsp;<a onclick="social_click({$meme->ID})" href="http://tec.fresqui.com/post?title={$meme->sociable_title}&url={$meme->sociable_link}"><img src="/sbs/fresqui.png" border="0" alt="fresqui"/></a>
  &nbsp;<a onclick="social_click({$meme->ID})" href="http://meneame.net/submit.php?url={$meme->sociable_link}"><img src="/sbs/meneame.png" border="0" alt="meneame"/></a>
  &nbsp;<a onclick="social_click({$meme->ID})" href="http://www.neodiario.net/submit.php?url={$meme->sociable_link}"><img src="/sbs/neodiario.png" border="0" alt="neodiario"/></a>
  &nbsp;<iframe src="http://rank.blogalaxia.com/pbrate.php?color=CCEEAF&url={$meme->sociable_link}" width=70 height=15 scrolling=no frameborder=0 marginheight=0 marginwidth=0 style='margin:0; padding:0'></iframe><br/>
  Vistas: {$meme->views}&nbsp;|&nbsp;Enlaces: {$meme->shares}&nbsp;|&nbsp;Clicks: {$meme->clicks}&nbsp;|&nbsp;Compartido: {$meme->social_clicks} veces
  </td></tr>
  
</table>
</div>
{/capture}
{$smarty.capture.layout}
