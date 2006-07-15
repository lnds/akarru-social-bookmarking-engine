<p>
{#profile_edit_instructions#}
</p>
{include file="form_header.tpl"}
<table width="360" border="0">
{if $error_profile}<tr><td class="error">{#error_profile#}</td></tr>{/if}
<tr><td class="view-label-class"><b>{#profile_fullname_label#}</b></td></tr>
<tr><td><input type="text" id="fullname" name="fullname" value="{$fullname}" class="view-input-class" size="60" /></td></tr>
{if $error_email_exists }<tr><td class="error">{#error_email_exists#}</td></tr>{/if}
{if $error_email }<tr><td class="error">{#error_email#}</td></tr>{/if}
<tr><td class="view-label-class"><b>{#profile_email_label#}</b></td></tr>
<tr><td><input type="text" id="email" name="email" value="{$email}" class="view-input-class" size="60" /></td></tr>

<tr><td class="view-label-class"><b>{#profile_website_label#}</b></td></tr>
<tr><td><input type="text" id="website" name="website" value="{$website}" class="view-input-class" size="60" /></td></tr>

<tr><td class="view-label-class"><b>{#profile_blog_label#}</b></td></tr>
<tr><td><input type="text" id="blog" name="blog" value="{$blog}" class="view-input-class" size="60" /></td></tr>
{if $is_admin}
<tr><td>
{#label_ban_user#}&nbsp;<input type="checkbox" id="ban_user" name="ban_user" value="1" {if $banned}checked="checked"{/if} />
</td></tr>
{/if}
<tr><td><input type="hidden" id="user_id" name="user_id" value="{$user_id}" /><input type="submit" id="do_profile_edit" name="do_profile_edit" value="{#label_profile_submit#}" /></td></tr>

</table>
{include file="form_footer.tpl"}
<script type="text/javascript">
document.getElementById('fullname').focus();
</script>