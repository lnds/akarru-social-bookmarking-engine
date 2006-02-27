{include file="form_header.tpl"}
<table border="0" cellpadding="2" cellspacing="0" width="360">
{if $error_login}<tr><td class="error">{#error_login#}</td></tr>{/if}
<tr><td class="view-label-class"><font color="red">*</font> <b>{#label_login_user#}</b></td></tr>
<tr><td><input type="text" name="user" value="{$user}" class="view-input-class" size="30" /></td></tr>
{if $error_user }<tr><td class="error">{#error_user#}</td></tr>{/if}
<tr><td class="view-label-class"><font color="red">*</font> <b>{#label_login_password#}</b></td></tr>
<tr><td><input type="password" name="pass" class="view-input-class" size="30" /></td></tr>
{if $error_pass }<tr><td class="error">{#error_pass#}</td></tr>{/if}
<tr><td><input type="hidden" name="from" value="{$from}" /><input type="submit" name="do_login" value="{#label_login_submit#}" /></td></tr>
</table>
<p><a href="recover_pass.php">{#label_login_recover_url#}</a></p>
{include file="form_footer.tpl"}
<div style="height:400px">&nbsp;</div>
