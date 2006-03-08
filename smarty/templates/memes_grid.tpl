{if ($bm_message) }
<p>{$bm_message}
{/if}
{#welcome_message#}

{foreach from=$memes item=meme name=mg}
<div class="meme-bg">
<table width="440" border="0" cellspacing="2" cellpadding="2" >
  <tr >
  <td colspan="2">
    <div class="votos" >
	  <h3  id="vote_count_{$meme->ID}" style="text-align:center" align="center">
	  {$meme->vote_count}
	  </h3>
	  <p>{#votes_label#}</p>
	   <div class="vote-class">
	{if $logged_in }
	<a class="vote-class" href="#vote_count_{$meme->ID}" onclick="update_vote_div('vote_count_{$meme->ID}','{$logged_userid}')">
	<img src="styles/img/meme-votar.png" border="0" alt="{#votes_label#}" /></a>
	{else}
	<a class="vote-class" href="#vote_count_{$meme->ID}" onclick="update_vote_div('vote_count_{$meme->ID}','0')"><img src="styles/img/meme-votar.png" border="0" alt="{#votes_label#}" /></a>
	{/if}
	</div>
    </div>
    <div class="meme-content"><br/><a href="{$meme->url}" onclick="goto_url({$meme->ID})">{$meme->title}</a>
	<div class="whowhen-class">&nbsp;{#posted_by_label#}&nbsp;<img src="{$meme->small_gravatar}" />&nbsp;<a class="whowhen-class" 
	style="font-size:10px" href="profile.php?user_name={$meme->username}">{$meme->username}</a>
	&nbsp;{$meme->date_posted|date_format:$bf_date_posted} 
	{if $meme->vote_count > 7}&nbsp;|&nbsp <a href="circulation.php?meme_id={$meme->ID}">circulaci&oacute;n</a>{/if}</div>
	<p style="right-margin:1em">
	  	  {$meme->content|nl2br}
	  </p>
    </div>
  </td>
  </tr>
  <tr><td colspan="2">&nbsp;&nbsp;<div style="padding-left:2em" class="meme-footer-class"><a href="comment.php?meme_id={$meme->ID}">{#comments_label#} {$meme->comment_count}</a> | <a href="tag_meme.php?meme_id={$meme->ID}">{#tag_meme_label#}</a> | <a href="{$meme->url}"  onclick="goto_url({$meme->ID})">{#url_label#}</a> | {#cat_label#} : <a href="show_cat.php?cat_id={$meme->cat_id}">{$meme->cat_title}</a>&nbsp;{if $meme->vote_count >= 7}|&nbsp <a href="circulation.php?meme_id={$meme->ID}">circulaci&oacute;n</a>{/if}</div>
  </td></tr>
</table>

<div class="infoboxFooter"><p>&nbsp;</p></div>
</div>
<br/>
{/foreach}
{include file="paginate.tpl"}
<div>
</div>
