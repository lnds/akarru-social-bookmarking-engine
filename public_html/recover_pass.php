<?php
  include_once('akarru.lib/common.php');
  if (empty($_POST['email'])) {
	  $bm_content = 'recover_pass';
  }
  else
  {
	  $email = $_POST['email'];
	  $subject = '['.$bm_site_name.'] '.$bl_recover;
	  $pass = $bm_users->gen_password($email, $subject, $bf_recover_pass, $bm_login_url);
	  $bm_info_text = $bl_password_sent;
	  $bm_info_title = '&nbsp;' ;
	  $bm_content = 'password_sent';
  }
  $smarty->assign('content_title', $bl_recover_pass);
  $smarty->assign('content', $bm_content);
  $smarty->assign('show_ads', true);
  $smarty->display('master_page.tpl');
?>
