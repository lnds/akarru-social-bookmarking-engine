<table width="460" border="0" cellspacing="0" cellpadding="2" >
  <tr>
    <td class="votos">
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
	</td>
    <td class="meme-content"><a href="{$meme->url}"  onclick="goto_url({$meme->ID})">{$meme->title}</a>
	<div class="whowhen-class">{#posted_by_label#}&nbsp;<a class="whowhen-class" 
	style="font-size:10px" href="profile.php?user_name={$meme->username}">{$meme->username}</a>
	&nbsp;{$meme->date_posted|date_format:$bf_date_posted}</div>
	  <p>
	  	  {$meme->content|nl2br}
	  </p>
	<div class="meme-footer-class"><a href="comment.php?meme_id={$meme->ID}">{#comments_label#} {$meme->comment_count}</a> | <a href="tag_meme.php?meme_id={$meme->ID}">{#tag_meme_label#}</a> | <a href="{$meme->url}"  onclick="goto_url({$meme->ID})">{#url_label#}</a> | {#cat_label#} : <a href="show_cat.php?cat_id={$meme->cat_id}">{$meme->cat_title}</a> </div></td>
  </tr>
</table>
<hr />
<table width="360" cellpadding="2" cellspacing="2">
<tr><td>
{foreach from=$meme_tags item=tag}
<a href="memes_by_tag.php?tag_id={$tag->ID}">{$tag->tag}</a>
{/foreach}
</td></tr>
</table>
{if $logged_in}
{include file="form_header.tpl"}
<table>
<tr><td class="view-label-class">{#tag_label#}</td></tr>
<tr><td><input type="text" name="tags" class="view-input-class" size="60" /></td></tr>
<tr><td><input type="hidden" name="meme_id" value="{$meme_id}" /><input class="view-button-class" type="submit" name="{#tag_submit_label#}" value="{#tag_submit_label#}" /></td></tr>
</table>
{include file="form_footer.tpl"}
{/if}
