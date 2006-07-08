<p>
{#account_validation_link_sent#}
</p>
{include file="form_header.tpl"}
<table border="0" cellpadding="2" cellspacing="0" width="360">
<tr><td class="view-label-class"><font color="red">*</font> <b>{#label_validation_code#}</b></td></tr>
<tr><td><input type="text" name="k" class="view-input-class" size="30" value="{$key}" /></td></tr>
{if $error_code}
<tr><td class="error">{#error_code#}</td></tr>
{/if}
<tr><td><input type="submit" name="verify_validation_link_code" value="{#label_verify_validation_code#}" />
<input type="hidden" name="h" class="view-input-class" value="{$hash}" /></td></tr>
</table>
{include file="form_footer.tpl"}
<div style="height:400px">&nbsp;</div>

