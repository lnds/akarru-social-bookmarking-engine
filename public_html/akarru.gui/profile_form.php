<p>
<?= $bl_profile_edit_instructions ?>
</p>
<div class="infobox">
<div style="padding-left:2em;">
<?php
print 
form(
	input_table('width="360"',
		input_cell_text($bl_fullname, 'fullname', '', 60, 0).
		input_cell_text($bl_email, 'email', $bm_error_mail, 60, 1).
		input_cell_text($bl_website, 'website', '', 60, 0).
        input_cell_text($bl_blog, 'blog', '', 60, 0).
		input_cell_pass($bl_password, 'pass', $bm_error_pass, 30, 0).
		input_cell_pass($bl_confirm_password, 'confirm_pass', $bm_error_confirm_pass, 30, 0).
		input_cell_submit($bl_profile_edit_submit, form_hidden('user_id', $user_id).form_hidden('do_edit', 1),
			$bm_form_error)
	)
);
?>
</div>
<div class="infoboxFooter"><p>&nbsp;</p></div>
</div>
