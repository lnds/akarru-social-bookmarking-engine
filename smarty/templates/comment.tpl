{if $voted}
<div>
<p>{#voted_thanks_message#}</p>
</div>
{else}
{if $alreadyvoted}
<div>
<p>{#already_voted_message#}</p>
</div>
{/if}
{/if}
{if $logged_and_valid}
{include file="meme_layout.tpl"}
{else}
{include file="meme_layout_anon.tpl"}
{/if}
{foreach from=$comments item=comment}
<div class="infobox" id="debate_form">
<div>
<div class="whowhen-class">
{$comment->date_posted|date_format:$bf_date_posted} 
&nbsp;{#comment_sender#}<a href="/user/{$comment->username}">{$comment->username}</a>&nbsp;<img src="{$comment->small_gravatar}" />&nbsp; 
</div>
<div style="padding:1em">{$comment->content|nl2br}</div>
</div>
<div class="infoboxFooter"><p>&nbsp;</p></div>
</div>
{/foreach}
{if $logged_and_valid}
{include file="form_header.tpl"}
<br />
<br />
<div class="infobox">
<div class="infoboxBody">
<p>&nbsp;</p>
<table width="360">
{if $error_comment}<tr><td class="error">{#error_comment#}</td></tr>{/if}
<tr><td class="view-label-class">{#comment_label#}</td></tr>
<tr><td><textarea name="comment" class="view-input-class" cols="40" rows="4">{$comment_value}</textarea></td></tr>
<tr><td class="view-label-class">{#debate_position_label#}</td></tr>
{if $meme->allows_debates}
<tr>
<td colspan="3" class="view-label-class">
{#position_label#}
<input type="radio" class="view-input-class" name="position" id="position" value="1">{#friend_posture#}</input>
<input type="radio" class="view-input-class" name="position" id="position" value="-1">{#foe_posture#}</input>
<input type="radio" class="view-input-class" name="position" id="position" value="0">{#neutral_posture#}</input>
</td>
</tr>
{/if}
<tr><td><input type="hidden" name="meme_id" value="{$meme_id}" /><input class="view-button-class" type="submit" name="{#comment_submit_label#}" value="{#comment_submit_label#}" /></td></tr>
</table>
</div>
<div class="infoboxFooter"><p>&nbsp;</p></div>
</div>
{include file="form_footer.tpl"}

{else}
    {if $logged_in}
    <strong>{#comment_not_valid_account_label#}</strong>
    <br />
    <a href="/validate_user.php">{#comment_validate_user#}</a> | <a href="/send_validation.php">{#comment_send_validation_code#}</a>
    {else}
    <strong>{#comment_no_login_label#}</strong>
    <br />
    <a href="/login.php">{#comment_login#}</a> | <a href="/register.php">{#comment_register#}</a>
    {/if}
{/if}
{if $meme->allows_debates}
<hr />
<div id="debate" style="padding:1em">
<p>
<table width="430" border="0" cellspacing="2" cellpadding="2" style="border: 1px solid green"> 
<tr><td colspan="3" align="center"><h2>{#debate_area_label#}</h2></td></tr>
<tr><td colspan="3" style="border-bottom: 1px solid green">{#debate_help#}</td></tr>
<tr><td style="border-right: 1px solid green">
<h2>{#friends_label#}</h2>
{#friends_description#}
</td>
<td>&nbsp;</td>
<td style="border-left: 1px solid green" >
<h2>{#foes_label#}</h2>
{#foes_description#}
</td></tr>
<tr>
<td align="left" valign="top" style="border-right: 1px solid green">
{html_table loop=$friends table_attr='id="pos" border="0" cellpadding="4" align="center"' cols="3"}</td>
<td>&nbsp;</td>
<td align="right" valign="top" style="border-left: 1px solid green">
{html_table loop=$foes table_attr='id="neg" border="0" cellpadding="4" align="center"' cols="3"}</td>
</tr>
<tr>
<td colspan="3" align="center" style="border-top: 1px solid green"><h2>{#sponsors_label#}</h2>
{#sponsors_description#}
</td>
</tr>
<td colspan="3">
{html_table cols="7" table_attr='id="neutral" border="0" cellpadding="2" align="center"' loop=$sponsors}
</td>
<tr>
<td colspan="3" align="center" style="border-top: 1px solid green"><h2>{#neutrals_label#}</h2>
{#neutrals_description#}
</td>
</tr>
<tr>
<td colspan="3">
{html_table cols="7" table_attr='id="neutral" border="0" cellpadding="2" align="center"' loop=$neutrals}
</td>
</tr>
</table>
<p>
</div>
{else}
<hr/>
{#users_who_voted_for_this_meme#}
{html_table loop=$voters table_attr='id="voters" border="0" cellpaddig="2" align"center"' cols="7"}
{/if}
<script type="text/javascript" src="http://embed.technorati.com/embed/kru95iwk92.js"></script>
</p>
<div style="height:400px">&nbsp;</div>

