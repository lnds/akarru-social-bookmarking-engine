{include file="form_header.tpl"}
<table border="0" cellpadding="2" cellspacing="0" width="360">
{if $error_login}<tr><td class="error">{#error_login#}</td></tr>{/if}
<tr><td class="view-label-class"><font color="red">*</font> <b>{#label_login_user#}</b></td></tr>
<tr><td><input type="text" name="user" value="{$user}" class="view-input-class" size="30" /></td></tr>
{if $error_user }<tr><td class="error">{#error_user#}</td></tr>{/if}
{if $error_user_exists }<tr><td class="error">{#error_user_exists#}</td></tr>{/if}
<tr><td class="view-label-class"><font color="red">*</font> <b>{#label_login_password#}</b></td></tr>
<tr><td><input type="password" name="pass" class="view-input-class" size="30" /></td></tr>
{if $error_pass }<tr><td class="error">{#error_pass#}</td></tr>{/if}
<tr><td class="view-label-class"><font color="red">*</font> <b>{#label_login_confirm_password#}</b></td></tr>
<tr><td><input type="password" name="confirm_pass" class="view-input-class" size="30" /></td></tr>
{if $error_confirm_pass }<tr><td class="error">{#error_confirm_pass#}</td></tr>{/if}
{if $error_bad_pass}<tr><td class="error">{#error_bad_pass#}</td></tr>{/if}
<tr><td class="view-label-class"><font color="red">*</font> <b>{#label_login_email#}</b></td></tr>
<tr><td><input type="text" name="email" value="{$email}" class="view-input-class" size="30" /></td></tr>
{if $error_email }<tr><td class="error">{#error_email#}</td></tr>{/if}
{if $error_email_exists }<tr><td class="error">{#error_email_exists#}</td></tr>{/if}
<tr><td><input type="hidden" name="from" value="{$from}" /><input type="submit" name="do_login" value="{#label_register_submit#}" /></td></tr>
</table>
{include file="form_footer.tpl"}
