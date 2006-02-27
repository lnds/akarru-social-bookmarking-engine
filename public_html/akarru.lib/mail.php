<?php

function send_new_password($email, $pass, $site, $login_url)
{
	@mail($email, '['.$bm_site_name.'] '. $bl_recover_pass_subject, sprintf($bf_recover_pass, $pass));
}
?>
