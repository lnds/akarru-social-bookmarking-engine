{config_load file="site.conf"}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
{if $logged_in}
{include file='master_page_logged.tpl'}
{else}
{include file='master_page_anon.tpl'}
{/if}
