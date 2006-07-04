<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  if (!empty($_POST))  {

	  $user = $_POST['user'];
	  $pass = $_POST['pass'];
	  $email = $_POST['email'];
	  $confirm_pass = $_POST['confirm_pass'];
	  $bm_errors = 0;
	  if (empty($user) || ! preg_match('/^[a-z0-9_]+$/i', $user)) {
		  $smarty->assign('error_user', true);
          $smarty->assign('user', $user);
		  $bm_errors++;
	  }
	  else{
		  $smarty->assign('user', $user);
	  }
	  if (empty($pass)) {
		  $smarty->assign('error_pass', true);
		  $bm_errors++;
	  }
	  if (empty($confirm_pass)) {
		  $smarty->assign('error_confirm_pass', true);
		  $bm_errors++;
	  }
	  if (empty($email)) {
		  $smarty->assign('error_email', true);
          $smarty->assign('email', $email);
		  $bm_errors++;
	  }
	  else
		  $smarty->assign('email', $email);
	  if ($bm_errors == 0) {
		  if ($pass != $confirm_pass) {
			  $smarty->assign('error_bad_pass', true);
			  $bm_errors++;
		  }
		  if ($bm_users->check_email_exists($email)) {
			  $smarty->assign('error_email_exists', true);
			  $bm_errors++;
		  }
		  if ($bm_users->check_user_exists($user)) {
			  $smarty->assign('error_user_exists', true);
			  $bm_errors++;
		  }
	  }
	  if ($bm_errors == 0) {
		  if (!$bm_users->register_user($user, $email, $pass)) {
			  $smarty->assign('error_cant_register', true);
			  $bm_errors++;
		  }
	  }

	  if ($bm_errors == 0) 
	  {
		  if (empty($_GET['from'])) {
            $url = "/user/" . $user;
		  }
		  else
		  {
			  $url = $_GET['from'].'.php';
		  }
		  header("Location: $url");
		  exit();
		  return;
	  }
	  else
	  {
		  $smarty->assign('from', $_GET['from']);
    		  $smarty->assign('content_title', $content_title_register);
		  $smarty->assign('content', 'register');
	  }
  }
  else
  {
	  $smarty->assign('from', $_GET['from']);
	  $smarty->assign('content_title', $content_title_register);
	  $smarty->assign('content', 'register');
  }
  $smarty->display('master_page.tpl');
?>

