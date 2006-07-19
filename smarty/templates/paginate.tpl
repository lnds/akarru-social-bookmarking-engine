{if $pages > 1 }
<div id="paginas-temp"><b>{#pages_label#}</b>
{if $pages < 10}
    {section name="pages" start=1 loop=$pages}
    {if $page == $smarty.section.pages.index}
    <b>{$smarty.section.pages.index}</b> 
    {else}
    <a href="{$smarty.server.PHP_SELF}?page={$smarty.section.pages.index}{$query_ext}">{$smarty.section.pages.index}</a> 
    {/if}
    {/section}
{else}
<a href="{$smarty.server.PHP_SELF}?page=1{$query_ext}">1</a> 
    {if $page < 5}
        {section name="pages" start=2 loop=7}
        {if $page == $smarty.section.pages.index}
        <b>{$smarty.section.pages.index}</b> 
        {else}
        <a href="{$smarty.server.PHP_SELF}?page={$smarty.section.pages.index}{$query_ext}">{$smarty.section.pages.index}</a> 
        {/if}
        {/section}
        ...
        <a href="{$smarty.server.PHP_SELF}?page={$pages-1}{$query_ext}">{$pages-1}</a>
    {else}
        {if $page < $pages-4}
            ...
            {section name="pages" start=$page-3 loop=$page+3}
            {if $page == $smarty.section.pages.index}
            <b>{$smarty.section.pages.index}</b> 
            {else}
            <a href="{$smarty.server.PHP_SELF}?page={$smarty.section.pages.index}{$query_ext}">{$smarty.section.pages.index}</a> 
            {/if}
            {/section}
            ...
            <a href="{$smarty.server.PHP_SELF}?page={$pages-1}{$query_ext}">{$pages-1}</a>
        {else}
            ...
            {section name="pages" start=$pages-6 loop=$pages}
            {if $page == $smarty.section.pages.index}
            <b>{$smarty.section.pages.index}</b> 
            {else}
            <a href="{$smarty.server.PHP_SELF}?page={$smarty.section.pages.index}{$query_ext}">{$smarty.section.pages.index}</a> 
            {/if}
            {/section}
        {/if}
    {/if}
{/if}
</div>
{/if}