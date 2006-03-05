<?php
  include_once('akarru.lib/common.php');
  if (!$bm_users->is_logged_in()) {
	  header("Location: login.php");
	  exit();
	  return;
  }
  $user_id = $_GET['user_id'];
  if ($bm_users->get_user_id() != $user_id) {
	  header("Location: login.php");
	  exit();
	  return;
  }
  $bm_content = 'akarru.gui/profile_form.php';

  if (empty($_POST['do_edit'])) {
	  $_POST['user_id'] = $bm_users->get_user_id();
	  $_POST['email'] = $bm_users->user->email;
	  $_POST['website'] = $bm_users->user->website;
	  $_POST['blog'] = $bm_users->user->blog;
	  $_POST['fullname'] = $bm_users->user->fullname;
  }
  else
  {
	  if (empty($_POST['email'])) {
		  $bm_error_mail = $be_email_required;
	  }
	  else
	  {
		  if (!empty($_POST['pass'])) {
			  if (!$bm_users->change_password($user_id, $pass, $confirm_pass)) {
				  $bm_content = 'akarru.gui/profile_form.php';
				  $bm_error_confirm_pass = $be_pass_coincidence;
			  }
		  }

		  if ($bm_users->update_profile($_POST)) {
			  $user_name = $bm_users->get_user_name();
			  header("Location: profile.php?user_name=$user_name");
			  exit();
			  return;
		  }
		  else {
			  $bm_form_error = $be_cant_update_profile;
		  }
		 
	  }
  }
  $bm_title = $bl_profile_edit.' '.$bm_users->get_user_name();
  include_once('akarru.gui/master_page.php');
?>
