{config_load file="site.conf"}
<div style="padding-left:1em;font-size:11px">
  <table width="440" border="0">
  <tr><td><b>{#profile_username_label#}</b></td><td>{$user_profile->username}</td>
  <td rowspan=4" nowrap="nowrap"><img src="{$user_profile->gravatar}" alt="{$user_profile->fullname}" />
    <br/>{#profile_gravatar_label#}
  </td>
  </tr>
  <tr><td><b>{#profile_fullname_label#}</b></td><td>{$user_profile->fullname}</td></tr>
  <tr><td><b>{#profile_join_date#}</b></td><td>{$user_profile->join_date|date_format:"%d/%m/%Y %H:%M"}</td></tr>
  <tr><td><b>{#profile_blog_label#}</b></td><td><a href="{$user_profile->blog}">{$user_profile->blog}</a></td></tr>
  <tr><td><b>{#profile_website_label#}</b></td><td><a href="{$user_profile->website}">{$user_profile->website}</a></td></tr>
  </table>
  {if $logged_in and ($user_profile->username eq $logged_username) and not $banned_account}
	{if not $logged_and_valid}
    {#profile_not_valid_account#}
    <strong><a href="/validate_user.php">{#profile_validate_user_label#}</a></strong>&nbsp;|&nbsp;
    <strong><a href="/send_validation.php">{#profile_send_validation_label#}</a></strong>
    <br />
    {/if}
    <strong><a href="/edit-profile/">{#profile_modify_label#}</a></strong>
  {else}
    {if $is_admin}
    <strong>!! <a href="/edit-user/{$user_profile->id}">{#profile_modify_label#}</a> !!</strong>
    {/if}
  {/if}
</div>
<hr />
<div style="padding-left:1em;font-size:11px">
<table width="440" border="0" cellpadding="5" cellspacing="2">
	  <tr>
	  <td><b>{#profile_memes_label#}</b></td><td align="right"><a href="/user/{$user_profile->username}">{$user_profile->memes}</a></td>
	  <td><b>{#profile_votes_label#}</b></td><td align="right"><a href="/votes/{$user_profile->username}">{$user_profile->votes}</a></td>
	  </tr>
	  <tr>
	  <td><b>{#profile_votes_for_label#}</b></td><td align="right">{$user_profile->memes_votes}</td>
	  <td><b>{#profile_comments_label#}</b></td><td align="right"><a href="/comments/{$user_profile->username}">{$user_profile->comments}</a></td></tr> 
	  <tr>
	  <td><b>{#profile_popularity_label#}</b></td><td align="right">{$user_profile->popularity|string_format:"%4.2f"}</td>
	  <td><b>{#profile_influence_label#}</b></td><td align="right">{$user_profile->influence|string_format:"%4.2f"}</td>
	  </tr>
  </table>
</div>
<hr />
<h3>{$profile_title}</h3>
{if $logged_and_valid}
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
