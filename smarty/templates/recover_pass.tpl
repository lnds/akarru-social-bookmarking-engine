{include file="form_header.tpl"}
<table border="0" cellpadding="2" cellspacing="0" width="360">
<tr><td class="view-label-class"><font color="red">*</font> <b>{#label_login_email#}</b></td></tr>
<tr><td><input type="text" name="email" class="view-input-class" size="30" /></td></tr>
{if $error_email}
<tr><td class="error">{#error_email#}</td></tr>
{/if}
<tr><td><input type="submit" name="recover_pass" value="{#label_recover_pass_submit#}" /></td></tr>
</table>
{include file="form_footer.tpl"}
<div style="height:400px">&nbsp;</div>
