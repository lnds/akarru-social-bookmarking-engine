<div class="infobox">
<h3>{#profile_personal_info_title#}</h3>
<div class="infoboxBody">
<div style="padding-left:1em;font-size:11px">
  <table width="240" border="0">
  <tr><td><b>{#profile_username_label#}</b></td><td>{$user_profile->username}</td><td rowspan=4" nowrap="nowrap"><img src="{$user_profile->gravatar}" alt="{$user_profile->fullname}" /><br/>{#profile_gravatar_label#}</td></tr>
  <tr><td><b>{#profile_fullname_label#}</b></td><td>{$user_profile->fullname}</td></tr>
  <tr><td><b>{#profile_join_date#}</b></td><td>{$user_profile->join_date|date_format:"%d/%m/%Y %H:%M"}</td></tr>
  <tr><td><b>{#profile_blog_label#}</b></td><td><a href="{$user_profile->blog}">{$user_profile->blog}</a></td></tr>
  <tr><td><b>{#profile_website#}</b></td><td><a href="{$user_profile->website}">{$user_profile->website}</a></td></tr>
  </table>
</div>
<div style="padding-left:1em">
{if $user_profile->username eq $logged_username}
<a href="profile_edit.php?user_id={$logged_userid}">{#profile_modify_label#}</a><br/>
{/if}
<h2>{#profile_stat_label#}</h2>
<div style="padding-left:1em;font-size:10px;">
  <table width="340" border="0" cellpadding="5" cellspacing="2">
	  <tr><td><b>{#profile_popularity_label#}</b></td><td align="right">{$user_profile->popularity|string_format:"%4.2f"}</td>
	  <td><b>{#profile_influence_label#}</b></td><td align="right">{$user_profile->influence|string_format:"%4.2f"}</td></tr>
	  <tr><td><b>{#profile_memes_label#}</b></td><td align="right">{$user_profile->memes}</td>
	  <td><b>{#profile_votes_label#}</b></td><td align="right">{$user_profile->votes}</td></tr>
	  <tr><td><b>{#profile_votes_for_label#}</b></td><td align="right">{$user_profile->memes_votes}</td>
	  <td><b>{#profile_comments_label#}</b></td><td align="right">{$user_profile->comments}</td></tr> 
  </table>
 </div>
</div>
</div>
  <div class="infoboxFooter"><p>&nbsp;</p></div>
</div>
<h3>{#profile_memes_by_user_label#}</h3>
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
	<div class="whowhen-class">{#posted_by_label#}&nbsp;<a class="whowhen-class" 
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
