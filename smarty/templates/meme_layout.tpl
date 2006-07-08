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
  {if !$meme->disabled}
  <tr>
  <td colspan="3">
  {if $meme->promoted}<div class="votos">{else}<div class="votosqueue">{/if}
  <h3 id="vote_count_{$meme->ID}" style="text-align:center" align="center">
  {if $meme->voted <= 0}
	<a class="vote-class" href="#vote_count_{$meme->ID}" onclick="update_vote_div('vote_count_{$meme->ID}','{$logged_userid}')">
	  {$meme->votes}</a>
  {else}
      {$meme->votes}
  {/if}
   </h3>	  
	{if $meme->voted <= 0}
	<p id="vote_label_{$meme->ID}">{#votes_label#}</p>
    {else}
    <p id="vote_label_{$meme->ID}">{#votes_label#}</p>
	{/if}
	<div class="vote-class" id="vote_button_{$meme->ID}">
	{if $meme->voted <= 0}
	<a class="vote-class" href="#vote_count_{$meme->ID}" onclick="update_vote_div('vote_count_{$meme->ID}','{$logged_userid}')">投票する</a>
	{/if}
	</div>
    </div>
    <div class="meme-content"><br/>
    <a href="{$meme->url}" target="_blank" onmousedown="return goto_url({$meme->ID},'{$meme->url}')">{$meme->title}</a>
	<div class="whowhen-class">
	&nbsp;{#cat_label#}: <a style="font-size:10px" href="/show_cat.php?cat_id={$meme->cat_id}">{$meme->cat_title}</a>
	&nbsp;{$meme->date_posted|date_format:$bf_date_posted}  
	<br />&nbsp;{#posted_by_label#}&nbsp;
	<a href="/user/{$meme->username}"><img src="{$meme->small_gravatar}" alt="{$meme->username}" border="0"/></a><br/>
	<a style="font-size:10px" href="/user/{$meme->username}">{$meme->username}</a>
	</div>
	<p style="right-margin:1em">
	{if $meme->page_image}
	<a href="{$meme->url}" target="_blank" onmousedown="return goto_url({$meme->ID},'{$meme->url}')">
	<img border="0" style="float:right;border:none" src="{$meme->page_image}" alt="snapshot" />
	</a>
	{/if}
	{if $meme->micro_content}
	<span style="padding:4px">{$meme->micro_content}</span><br />
	{if empty($page)}
	<a href="/videos.php">{#see_more_videos#}</a><br/>
	{/if}
	{/if}
	  	  {$meme->content|nl2br}
		  <br/>
	{if $meme->allows_debates}
	  <a href="{$meme->permalink}#debate">{#debate_label#} {#friend_posture#}: {$meme->debate_pos}, {#foe_posture#}: {$meme->debate_neg}</a>
	{else}
 	  <a  href="{$meme->permalink}">{#comments_label#} {$meme->comments}</a>  
	{/if}
	  </p>
    </div>
     </td></tr>
	{if $tags_of_meme}
     <tr><td colspan="3"><b>{#tags_label#}</b>
	{html_table loop=$tags_of_meme table_attr='border="0" cellspacing="4" cellpadding="4" ' cols="6"}
	</td></tr>
    {/if}
  <tr><td colspan="3"><div style="padding-left:1em" class="meme-footer-class"> 
  {if $logged_userid == $meme->submitted_user_id || $logged_userid == 1}<a href="/edit-meme/{$meme->ID}">{#edit_label#}編集</a>&nbsp;|&nbsp;{/if}{#comments_label#} <a href="/meme/{$meme->ID}">{$meme->comments}</a>&nbsp;|&nbsp;<a href="/tag-meme/{$meme->ID}">{#tag_meme_label#}</a> | {#cat_label#}: <a href="/show_cat.php?cat_name={$meme->cat_title}">{$meme->cat_title}</a> | {#url_label#}&nbsp;<a href="{$meme->url}"  onmousedown="return goto_url({$meme->ID},'{$meme->url}')">{$meme->url|truncate:30:"..."}</a>&nbsp;</div>
  <tr><td colspan="3"><div style="padding-left:1em" class="meme-footer-class">
  {include file="meme_stats.tpl"}
  <br />
  {include file="social_tools.tpl"}
  </div>
  </td></tr>
  {else}
  <tr>
  <td colspan="3">
  <div class="meme-content"><br/>
    <p style="right-margin:1em">
       {#disabled_meme#}
	</p>
    </div>
     </td></tr>
  <tr><td colspan="3"><div style="padding-left:1em" class="meme-footer-class"> 
  {if $logged_userid == 1}<a href="/edit-meme/{$meme->ID}">{#edit_label#}編集</a>{/if}</div> 
  </td></tr>
  {/if}
</table>
</div>