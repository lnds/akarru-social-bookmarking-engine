<div class="meme-bg">
<table width="430" border="0" cellspacing="0" cellpadding="2" >
  <tr>
    <td colspan="2">
    <div class="votos" >
	  <h3  id="vote_count_{$meme->ID}" style="text-align:center" align="center">
	  {$meme->vote_count}
	  </h3>
	  <p>{#votes_label#}</p>
	   <div class="vote-class">
	{if $logged_in }
	<a class="vote-class" href="#" onclick="update_vote_div('vote_count_{$meme->ID}','{$logged_userid}')">
	<img src="styles/img/meme-votar.png" border="0" alt="{#votes_label#}" /></a>
	{else}
	<a class="vote-class" href="login.php" ><img src="styles/img/meme-votar.png" border="0" alt="{#votes_label#}" /></a>
	{/if}
	</div>
    </div>
    <div class="meme-content"><br/><a href="{$meme->url}"  onclick="goto_url({$meme->ID})">{$meme->title}</a>
	<div class="whowhen-class">&nbsp;{#posted_by_label#}&nbsp;<img src="{$meme->small_gravatar}" />&nbsp;<a class="whowhen-class" 
	style="font-size:10px" href="profile.php?user_name={$meme->username}">{$meme->username}</a>
	&nbsp;{$meme->date_posted|date_format:$bf_date_posted}
	{if $meme->vote_count > 7}&nbsp;|&nbsp <a href="circulation.php?meme_id={$meme->ID}">circulaci&oacute;n</a>{/if}</div>
	</div>
	{if $meme->micro_content}
	<div style="padding:2px">{$meme->micro_content}</div>
	{/if}
	
	  <p>
	  	  {$meme->content|nl2br}
	  </p>
     </div>
     </td></tr>
  <tr><td colspan="2">&nbsp;&nbsp;<div style="padding-left:2em" class="meme-footer-class"><a href="#voters">{#voters_label#}</a> | <a href="tag_meme.php?meme_id={$meme->ID}">{#tag_meme_label#}</a> | <a href="{$meme->url}"  onclick="goto_url({$meme->ID})">{#url_label#}</a> | {#cat_label#} : <a href="show_cat.php?cat_id={$meme->cat_id}">{$meme->cat_title}</a>{if $meme->vote_count >= 7}|&nbsp <a href="circulation.php?meme_id={$meme->ID}">circulaci&oacute;n</a>{/if}</div></td>
  </td></tr>
</table>
<div class="infoboxFooter"><p>&nbsp;</p></div>
</div>

<hr />
{foreach from=$comments item=comment}
<div class="infobox">
<div>
<div class="whowhen-class">
&nbsp;{#comment_sender#}<img src="{$comment->small_gravatar}" />&nbsp;<a class="whowhen-class" href="profile.php?user_name={$comment->username}">{$comment->username}</a> {$comment->date_posted|date_format:$bf_date_posted}
</div>
<div style="padding:1em">{$comment->content|nl2br}</div>
</div>
<div class="infoboxFooter"><p>&nbsp;</p></div>
</div>
{/foreach}
{if $logged_in}
{include file="form_header.tpl"}
<br />
<br />
<div class="infobox">
<div class="infoboxBody">
<table width="360">
<tr><td class="view-label-class">{#comment_label#}</td></tr>
<tr><td><textarea name="comment" class="view-input-class" cols="50" rows="4"></textarea></td></tr>
<tr><td><input type="hidden" name="meme_id" value="{$meme_id}" /><input class="view-button-class" type="submit" name="{#comment_submit_label#}" value="{#comment_submit_label#}" /></td></tr>
</table>
</div>
<div class="infoboxFooter"><p>&nbsp;</p></div>
</div>
{include file="form_footer.tpl"}

{else}
<strong>{#comment_no_login_label#}</strong>
<br />
<a href="login.php?from=comment">{#comment_login#}</a> | <a href="register.php?from=comment">{#comment_register#}</a>
{/if}
<hr />
<div id="voters" style="padding:1em">
<strong>Promotores</strong>
<p>
Estos son los usuarios que han votado a favor de esta historia, por qu&eacute; les ha parecido
que vale la pena:</p>
<p>
{foreach from=$voters item=voter}
<img src="{$voter->small_gravatar}" />&nbsp;<a href="profile.php?user_name={$voter->username}">{$voter->username}</a>&nbsp;&nbsp;
{/foreach}
</p>
<p>
Si no est&aacute;s de acuerdo, no la leas y no la votes.
<br/>
Si no te cuadran los votos con los votantes, es que se permiten votos an&oacute;nimos, y en las primeras versiones de este 
sistema hab&iacute;a un error que permit&iacute;a votar m&aacute;s de una vez, hemos decidido no alterar los datos hist&oacute;ricos.

</p>
<p>
</p>
</div>
<div style="height:400px">&nbsp;</div>

