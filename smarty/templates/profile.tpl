<div style="padding-left:1em;font-size:11px">
  <table width="440" border="0">
  <tr><td><b>{#profile_username_label#}</b></td><td>{$user_profile->username}</td>
  <td rowspan=4" nowrap="nowrap"><img src="{$user_profile->gravatar}" alt="{$user_profile->fullname}" />
    <br/>{#profile_gravatar_label#}
{if $user_profile->username eq $logged_username}
<br/><a href="profile_edit.php?user_id={$logged_userid}">{#profile_modify_label#}</a>
{/if}
  </td>
  </tr>
  <tr><td><b>{#profile_fullname_label#}</b></td><td>{$user_profile->fullname}</td></tr>
  <tr><td><b>{#profile_join_date#}</b></td><td>{$user_profile->join_date|date_format:"%d/%m/%Y %H:%M"}</td></tr>
  <tr><td><b>{#profile_blog_label#}</b></td><td><a href="{$user_profile->blog}">{$user_profile->blog}</a></td></tr>
  <tr><td><b>{#profile_website_label#}</b></td><td><a href="{$user_profile->website}">{$user_profile->website}</a></td></tr>
  </table>
</div>
<table width="440" border="0" cellpadding="5" cellspacing="2">
	  <tr>
	  <td><b>{#profile_memes_label#}</b></td><td align="right"><a href="/user/{$user_profile->username}">{$user_profile->memes} (ver)</a></td>
	  <td><b>{#profile_votes_label#}</b></td><td align="right"><a href="/votes/{$user_profile->username}">{$user_profile->votes} (ver)</a></td>
	  </tr>
	  <tr>
	  <td><b>{#profile_votes_for_label#}</b></td><td align="right">{$user_profile->memes_votes}</td>
	  <td><b>{#profile_comments_label#}</b></td><td align="right"><a href="/comments/{$user_profile->username}">{$user_profile->comments} (ver)</a></td></tr> 
	  <tr>
	  <td><b>{#profile_popularity_label#}</b></td><td align="right">{$user_profile->popularity|string_format:"%4.2f"}</td>
	  <td><b>{#profile_influence_label#}</b></td><td align="right">{$user_profile->influence|string_format:"%4.2f"}</td>
	  </tr>
  </table>
<h3>{$profile_title}</h3>
{if $logged_in}
{foreach from=$memes item=meme name=mg}
{include file="meme_layout.tpl"}
<hr />
{/foreach}
{else}
{foreach from=$memes item=meme name=mg}
{include file="meme_layout_anon.tpl"}
<hr />
{/foreach}
{/if}
{include file="paginate.tpl"}
<div style="height:1400px;_height:1400px">&nbsp;</div>
