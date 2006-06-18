<p>
{#folksonomy_label#}
</p>
<table width="460" border="0" cellspacing="2" cellpadding="2" >
	<tr>
		<td>
{foreach from=$all_tags item=tag}		
<a style="font-size:{$tag->font_size}pt;text-decoration:none" href="/tag/{$tag->tag}">{$tag->tag}</a>&nbsp; 
{/foreach}
</td>
</tr>
</table>
{include file='paginate.tpl'}

