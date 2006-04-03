{foreach from=$memes item=meme name=mg}

<div class="meme-bg">
<table width="440" border="0" cellspacing="0" cellpadding="2" >
  <tr><td>
  <div style="padding-left:1em" class="meme-footer-class"> 
  {if $meme->prior_meme}
  &lt;&nbsp;<a href="comment.php?meme_id={$meme->prior_meme->ID}">{$meme->prior_meme->title}</a>
  {/if}
  </div>
  <td>&nbsp;&nbsp;</td>
  <td>
  <div style="padding-right:1em" class="meme-footer-class"> 
  {if $meme->next_meme}
  <a href="comment.php?meme_id={$meme->next_meme->ID}">{$meme->next_meme->title}</a>&nbsp;&gt;
  {/if}
  </div>
  </td></tr>
  <tr>
  <td colspan="3">
    <div class="votos" >
	  <h3  id="vote_count_{$meme->ID}" style="text-align:center" align="center">
	  {$meme->votes}
	  </h3>
	{if $meme->voted <= 0}
	<p id="vote_label_{$meme->ID}">{#votes_label#}</p>
	{/if}
	<div class="vote-class" id="vote_button_{$meme->ID}">
	{if $logged_in }
	{if $meme->voted <= 0}
	<a class="vote-class" href="#vote_count_{$meme->ID}" onclick="update_vote_div('vote_count_{$meme->ID}','{$logged_userid}')">
	<img src="styles/img/meme-votar.png" border="0" alt="{#votes_label#}" /></a>
	{/if}
	{else}
	<a class="vote-class" href="#vote_count_{$meme->ID}" onclick="update_vote_div('vote_count_{$meme->ID}','0')"><img src="styles/img/meme-votar.png" border="0" alt="{#votes_label#}" /></a>
	{/if}
	</div>
    </div>
    <div class="meme-content"><br/>
    <a href="{$meme->url}" onclick="goto_url({$meme->ID},'{$meme->url}')">{$meme->title}</a>
	<div class="whowhen-class">
	&nbsp;{#cat_label#}: <a style="font-size:10px" href="show_cat.php?cat_id={$meme->cat_id}">{$meme->cat_title}</a>
	&nbsp;{$meme->date_posted|date_format:$bf_date_posted}  
	&nbsp;{#posted_by_label#}&nbsp;
	<a href="profile.php?user_name={$meme->username}"><img src="{$meme->small_gravatar}" border="0"/></a><br/>
	<a style="font-size:10px" href="profile.php?user_name={$meme->username}">{$meme->username}</a>
	</div>
	<p style="right-margin:1em">
	{if $meme->page_image}
	<a href="{$meme->url}">	
	<img border="0" style="float:right;border:none" src="{$meme->page_image}" alt="snapshot" />
	</a>
	{/if}
	{if $meme->micro_content}
	<div style="padding:4px">{$meme->micro_content}</div>
	{/if}
	  	  {$meme->content|nl2br}
		  <br/>
	{if $meme->allows_debates}
	  <a href="comment.php?meme_id={$meme->ID}#debate">{#debate_label#} {#friend_posture#}: {$meme->debate_pos}, {#foe_posture#}: {$meme->debate_neg}</a>
	  {/if}
	  {if $logged_user_id == $meme->submitted_user_id}
	  
	  {/if}
	  </p>
    </div>
  </td></tr>
  <tr><td colspan="3"><div style="padding-left:1em" class="meme-footer-class"> 
  {if $logged_userid == $meme->submitted_user_id}<a href="edit_meme.php?meme_id={$meme->ID}">modificar</a>&nbsp;|&nbsp;{/if}{#comments_label#} <a href="comment.php?meme_id={$meme->ID}">{$meme->comments}</a>&nbsp;|&nbsp;<a href="tag_meme.php?meme_id={$meme->ID}">{#tag_meme_label#}</a> | {#cat_label#}: <a href="show_cat.php?cat_id={$meme->cat_id}">{$meme->cat_title}</a> | {#url_label#}&nbsp;<a href="{$meme->url}" onclick="goto_url({$meme->ID},'{$meme->url}')">{$meme->url|truncate:30:"..."}</a>&nbsp;</div> 
  </td></tr>
</table>
</div>

<hr />
{/foreach}
{include file="paginate.tpl"}
<div>
<script type="text/javascript" src="http://embed.technorati.com/embed/kru95iwk92.js"></script>
<div>
<form Method="POST" action="http://www.feedblitz.com/f/f.fbz?AddNewUserDirect">
Recibe BlogMemes por Email<br><input name="EMAIL" maxlength="255" type="text" size="30" value=""><br>
<input name="FEEDID" type="hidden" value="30874">
<input type="submit" value="Subscribeme">
<br>Powered by <a href="http://www.feedblitz.com">FeedBlitz</a></form> 
				</div>

</div>

