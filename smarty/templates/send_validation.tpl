<p>
{#account_send_validation#}
<br />
{#profile_label#}<a href="/user/{$logged_username}">{$logged_username}</a>
</p>
{include file="form_header.tpl"}
<table border="0" cellpadding="2" cellspacing="0" width="360">
<tr><td class="view-label-class"><b>{#label_send_validation_email#}</b></td></tr>
<tr><td><input type="text" disabled="disabled" id="email" name="email" class="view-input-class" size="30" value="{$email}" /></td></tr>
{if $error_cant_send_validation_link}
<tr><td class="error">{#error_cant_send_validation_link#}</td></tr>
{/if}
<tr><td><input type="submit" id="send_validation_link_code" name="send_validation_link_code" value="{#label_send_validation_code#}" />
</table>
{include file="form_footer.tpl"}
<script type="text/javascript">
document.getElementById('send_validation_link_code').focus();
</script>
<div style="height:400px">&nbsp;</div>

