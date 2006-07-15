{config_load file="site.conf"}
<p>
{#folksonomy_label#}
</p>
<table width="460" border="0" cellspacing="2" cellpadding="2" >
	<tr>
		<td>
{foreach from=$all_tags item=tag}		
<span style="line-height:{$tag->font_size}px; font-size:{$tag->font_size}px;text-decoration:none"><a href="/memes_by_tag.php?tag_name={$tag->tag}" style="text-decoration:none">{$tag->tag}</a></span>&nbsp;|&nbsp;
{/foreach}
</td>
</tr>
</table>
{include file='paginate.tpl'}

