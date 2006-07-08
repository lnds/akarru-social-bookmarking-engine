{if $pages > 1 }
<div id="paginas-temp"><b>{#pages_label#}</b>
{if $pages > 20}    
    {if $page > 11}
    ...
    {/if}
    {if $pages > $page+10}
    {section name="pages" start=$page-10 loop=$pages max=20}
    {if $page == $smarty.section.pages.index}
    <b>{$smarty.section.pages.index}</b> 
    {else}
    <a href="{$smarty.server.PHP_SELF}?page={$smarty.section.pages.index}{$query_ext}">{$smarty.section.pages.index}</a> 
    {/if}
    {/section}
    ...
    {else}
    {section name="pages" start=$pages-20 loop=$pages max=20}
    {if $page == $smarty.section.pages.index}
    <b>{$smarty.section.pages.index}</b> 
    {else}
    <a href="{$smarty.server.PHP_SELF}?page={$smarty.section.pages.index}{$query_ext}">{$smarty.section.pages.index}</a> 
    {/if}
    {/section}
    {/if}
{else}
    {section name="pages" start=1 loop=$pages max=20}
    {if $page == $smarty.section.pages.index}
    <b>{$smarty.section.pages.index}</b> 
    {else}
    <a href="{$smarty.server.PHP_SELF}?page={$smarty.section.pages.index}{$query_ext}">{$smarty.section.pages.index}</a> 
    {/if}
    {/section}
{/if}
</div>
{/if}