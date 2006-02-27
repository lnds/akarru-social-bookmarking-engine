<?php
  include_once('akarru.lib/common.php');
  if (empty($_POST['email'])) {
	  $bm_content = 'akarru.gui/recover_pass_form.php';
  }
  else
  {
	  $subject = '['.$bm_site_name.'] '.$bl_recover;
	  $pass = $bm_users->gen_password($email, $subject, $bf_recover_pass, $bm_login_url);
	  $bm_info_text = $bl_password_sent;
	  $bm_info_title = '&nbsp;' ;
	  $bm_content = 'akarru.gui/info.php';
  }
  $bm_no_folkbar = true;
  $bm_title = $bl_recover_pass;
  include_once('akarru.gui/master_page.php');
?>
