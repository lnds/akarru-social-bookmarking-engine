{if $pages > 1 }
<div id="paginas-temp"><b>{#pages_label#}</b>
{section name="pages" start=1 loop=$pages}
{if $page == $smarty.section.pages.index}
<b>{$smarty.section.pages.index}</b> 
{else}
<a href="{$smarty.server.PHP_SELF}?page={$smarty.section.pages.index}{$query_ext}">{$smarty.section.pages.index}</a> 
{/if}
{/section}
</div>
{/if}
