<?php
  include_once('akarru.lib/common.php');
  if (!$bm_users->is_logged_in()) {
	  header("Location: /login.php");
	  exit();
	  return;
  }
  
  if ($bm_users->is_banned()) {
	  header("Location: /403.php");
	  exit();
	  return;
  }
  
  include_once('common_elements.php');

  if (empty($_POST))
  {
  	  $user_id = intval($_GET['user_id']);
	  $profile = $bm_users->get_user_profile_by_id($user_id);
	  $smarty->assign('user_id', $user_id);
	  $smarty->assign('email', $profile->email);
	  $smarty->assign('website', $profile->website);
	  $smarty->assign('blog', $profile->blog);
	  $smarty->assign('fullname', $profile->fullname);
  }
  else
  {
	  if (empty($_POST['email'])) {
		  $smarty->assign('error_email', 1);
	  }
	  else
	  {
		  if (!empty($_POST['pass'])) {
			  $pass = $_POST['pass'];
			  $confirm_pass = $_POST['confirm_pass'];
			  if (!$bm_users->change_password($user_id, $pass, $confirm_pass)) 
				  $smarty->assign('error_pass', true);
                  $smarty->assign('user_id', $bm_users->get_user_id());
                  $smarty->assign('email', $bm_users->user->email);
                  $smarty->assign('website', $bm_users->user->website);
                  $smarty->assign('blog', $bm_users->user->blog);
                  $smarty->assign('fullname', $bm_users->user->fullname);
              }
              else
              {
                  $user_name = $bm_users->get_user_name();
                  header("Location: /user/$user_name");
                  exit();
                  return;
              }
		  }
		  else if ($bm_users->update_profile($_POST)) {
			  $user_name = $bm_users->get_user_name();
              $smarty->clear_cache(null,'db|users|profile|' . $user_name);
			  header("Location: /user/$user_name");
			  exit();
			  return;
		  }
		  else {
			  $smarty->assign('error_profile', true);
		  }
	  }
  }
  $bm_title = $bl_profile_edit.' '.$bm_users->get_user_name();
  $smarty->assign('content_title', $bm_title);
  $smarty->assign('content', 'profile_edit');
  $smarty->display('master_page.tpl');
?>
