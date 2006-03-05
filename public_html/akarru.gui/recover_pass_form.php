<div class="infobox">
<p style="padding-left:2em;padding-top:0.5em;padding-right:1em;">
<?= $bl_recover_pass_instructions ?>
<div style="padding-left:2em;padding-top:0.5em;padding-right:1em;">
<?php
print 
form(
	input_table('width="360"',
		input_cell_text($bl_email, 'email').
		input_cell_submit($bl_recover_submit, form_hidden('do_recover', 1))
	)
);
?>
</div>
<div class="infoboxFooter"><p>&nbsp;</p></div>
</div>
