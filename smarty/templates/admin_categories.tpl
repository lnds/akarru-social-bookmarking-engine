<h3>{#categories_label#}</h3>
<table border="0" cellspacing="0"cellpadding="0">
<tr><td>Category&nbsp;</td><td>#Memes&nbsp;</td><td>State&nbsp;</td><td>Toggle&nbsp;</td><td>Delete&nbsp;</td><td>Edit&nbsp;</td></tr>
{foreach from=$bm_cats item=cat}
<tr><td><a href="show_cat.php?cat_id={$cat->ID}">{$cat->cat_title}</a></td><td align="right">{$cat->post_count}</td>
<td>{if $cat->disabled}<img src="styles/img/disabled.gif" width="16" height="16" alt="disabled" title="{$cat->cat_title} is disabled" />{else}<img src="styles/img/enabled.gif" width="16" height="16" alt="enabled" title="{$cat->cat_title} is enabled" />{/if}</td>
<td>{if $cat->disabled}<a href="{$smarty.server.PHP_SELF}?enable={$cat->ID}" title="Enable {$cat->cat_title}"><img src="styles/img/toggle.gif" style="border:0px" width="16" height="16" /></a>{else}<a href="{$smarty.server.PHP_SELF}?disable={$cat->ID}" title="Disable {$cat->cat_title}"><img src="styles/img/toggle.gif" style="border:0px" width="16" height="16" /></a>{/if}</td>
<td>{if $cat->post_count==0}<a href="{$smarty.server.PHP_SELF}?delete={$cat->ID}" title="Delete {$cat->cat_title}"><img src="styles/img/delete.gif" style="border:0px" width="16" height="16" /></a>{else} {/if}</td>
<td><a href="{$smarty.server.PHP_SELF}?edit={$cat->ID}" title="Edit {$cat->cat_title}"><img src="styles/img/edit.gif" style="border:0px" width="16" height="16" /></td></tr>
{/foreach}
</table>