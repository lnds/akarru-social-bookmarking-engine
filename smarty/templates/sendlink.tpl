<form method="post" action="{$smarty.server.PHP_SELF}">
<div>
<h3>{#sendlink_title#}</h3>
<p>
{#email_criteria#}
</p>
<table border="0" cellpadding="2" cellspacing="0" width="300" align="center">
<tr><td>&nbsp;</td></tr>
<tr><td class="view-label-class"><font color="red">*</font>{#subject_email_label#}</td></tr>
<tr><td><input class="view-input-class" type="text" id="email_subject" name="email_subject" value="{$email_subject}" size="60" /></td></tr>
<tr><td class="view-label-class"><font color="red">*</font>{#sender_email_label#}</td></tr>
{if $error_sender_email}<tr><td class="error">{#error_sender_email#}</td></tr>{/if}
<tr><td><input class="view-input-class" type="text" id="sender_email" name="sender_email" value="{$sender_email}" size="60" /></td></tr>
<tr><td class="view-label-class">{#sender_name_label#}</td></tr>
<tr><td><input class="view-input-class" type="text" id="sender_name" name="sender_name" value="{$sender_name}" size="60" /></td></tr>
<tr><td class="view-label-class"><font color="red">*</font>{#receiver_email_label#}</td></tr>
{if $error_receiver_email}<tr><td class="error">{#error_receiver_email#}</td></tr>{/if}
<tr><td><input class="view-input-class" type="text" id="receiver_email" name="receiver_email" value="{$receiver_email}" size="60" /></td></tr>
<tr><td class="view-label-class">{#receiver_name_label#}</td></tr>
<tr><td><input class="view-input-class" type="text" id="receiver_name" name="receiver_name" value="{$receiver_name}" size="60" /></td></tr>
<tr><td class="view-label-class">{#message_email_label#}</td></tr>
{if $error_message_body}<tr><td class="error">{#error_message_body#}</td></tr>{/if}
<tr><td><textarea id="message_body" name="message_body" class="view-input-class" cols="45" rows="4">{$message_body}</textarea></td></tr>
<tr><td class="view-label-class"><font color="red">*</font>{#automatic_message_email_label#}</td></tr>
<tr><td>{#automatic_message_email#|replace:'%USER%':$username|replace:'%TITLE%':$meme->title|replace:'%MEME_URL%':$permalink|replace:'%TEXT%':$meme->content|replace:'%VOTES%':$meme->votes}</td></tr>
<tr><td><input type="hidden" id="meme_id" name="meme_id" value="{$meme_id}" /><input type="hidden" id="step" name="step" value="{$step}" /><input class="view-button-class" type="submit" value="{#send_email_label#}" id="do_post" name="do_post" /></td></tr>
</table>
</div>
</form>
{if $logged_in}
<script type="text/javascript">
document.getElementById('receiver_email').focus();
</script>
{else}
<script type="text/javascript">
document.getElementById('sender_email').focus();
</script>
{/if}
<div style="height:1400px;_height:1400px">&nbsp;</div>