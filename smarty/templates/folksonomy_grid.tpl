<p>
{#folksonomy_label#}
</p>
<table width="460" border="0" cellspacing="2" cellpadding="2" >
	<tr>
		<td>
{foreach from=$all_tags item=tag}		
<a style="font-size:{$tag->font_size}pt;text-decoration:none" href="memes_by_tag.php?tag_id={$tag->tag_id}">{$tag->tag}</a>&nbsp; 
{/foreach}
</td>
</tr>
</table>

