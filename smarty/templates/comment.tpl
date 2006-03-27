<div class="meme-bg">
<table width="430" border="0" cellspacing="0" cellpadding="2" >
  <tr>
    <td colspan="2">
    <div class="votos" >
	  <h3  id="vote_count_{$meme->ID}" style="text-align:center" align="center">
	  {$meme->votes}
	  </h3>
	  <p id="vote_label_{$meme->ID}">{#votes_label#}</p>
	   <div class="vote-class"  id="vote_button_{$meme->ID}">
	{if $logged_in }
	{if $meme->voted <= 0}
	<a class="vote-class" href="#" onclick="update_vote_div('vote_count_{$meme->ID}','{$logged_userid}')">
	<img src="styles/img/meme-votar.png" border="0" alt="{#votes_label#}" /></a>
	{/if}
	{else}
	<a class="vote-class" href="login.php" ><img src="styles/img/meme-votar.png" border="0" alt="{#votes_label#}" /></a>
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
	  </p>
     </div>
     </td></tr>
  <tr><td colspan="2"><div style="padding-left:1em" class="meme-footer-class"> 
  {#debate_label#}: <a href="#debate">{$meme->debate_pos}/{$meme->debate_0}/{$meme->debate_neg}</a>&nbsp;|&nbsp;<a href="tag_meme.php?meme_id={$meme->ID}">{#tag_meme_label#}</a> | {#cat_label#}: <a href="show_cat.php?cat_id={$meme->cat_id}">{$meme->cat_title}</a> | {#url_label#}&nbsp;<a href="{$meme->url}" onclick="goto_url({$meme->ID},'{$meme->url}')">{$meme->url|truncate:20:"..."}</a>&nbsp;</div> 
  </td></tr>
</table>
</div>
{foreach from=$comments item=comment}
<div class="infobox" id="debate_form">
<div>
<div class="whowhen-class">
{$comment->date_posted|date_format:$bf_date_posted} 
&nbsp;{#comment_sender#}<a href="profile.php?user_name={$comment->username}">{$comment->username}</a>&nbsp;<img src="{$comment->small_gravatar}" />&nbsp; 
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
<tr><td class="view-label-class">{#debate_position_label#}</td></tr>
<tr>
<td colspan="3">
{#position_label#}
<input type="radio" name="position" id="position" value="1">a favor</input>
<input type="radio" name="position" id="position" value="-1">en contra</input>
<input type="radio" name="position" id="position" value="0">no me decido</input>
</td>
</tr>
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
<div id="debate" style="padding:1em">
<p>
<table width="430" border="0" cellspacing="2" cellpadding="2" style="border: 1px solid green"> 
<tr><td colspan="3" align="center"><h2>{#debate_area_label#}</h2></td></tr>
<tr><td colspan="3" style="border-bottom: 1px solid green">{#debate_help#}</td></tr>
<tr><td style="border-right: 1px solid green"><h2>{#friends_label#}</h2></td>
<td>&nbsp;</td>
<td style="border-left: 1px solid green" ><h2>{#foes_label#}</h2></td></tr>
<tr>
<td align="left" valign="top" style="border-right: 1px solid green">{html_table loop=$friends table_attr='border=0 cellpadding=4 align=center' cols="3"}</td>
<td>&nbsp;</td>
<td align="right" valign="top" style="border-left: 1px solid green">{html_table loop=$foes table_attr='border=0 cellpadding=4 align=center' cols="3"}</td>
</tr>
<tr>
<td colspan="3" align="center" style="border-top: 1px solid green"><h2>{#neutrals_label#}</h2></td>
</tr>
<tr>
<td colspan="3">
{html_table cols="7" table_attr="border=0 cellpadding=2 align=center" loop=$neutrals}
</td>
</table>
<p>
</p>
</div>
<div style="height:400px">&nbsp;</div>

