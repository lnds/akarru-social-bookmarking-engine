<?php
  include_once('akarru.lib/common.php');
  if (!empty($_POST))
  {

	  $user = $_POST['user'];
	  $pass = $_POST['pass'];
	  $bm_errors = 0;
	  if (empty($user)) {
		  $smarty->assign('error_user', true);
		  $bm_errors++;
	  }
	  else{
		  $smarty->assign('user', $user);
	  }
	  if (empty($pass)) {
		  $smarty->assign('error_pass', true);
		  $bm_errors++;
	  }
	  if ($bm_errors == 0) {
		  if ($bm_users->do_login($_POST['user'],$_POST['pass'],$_POST['remember'])) {
			  $url = $_POST['from'];
			  header("Location: $url");
			  exit;
			  return;
		  }
		  else
			  $smarty->assign('error_login', true);
	  }
  }
  if (empty($_GET['from'])) {
	  $from = $bm_home;
  }
  else
  {
	  $from = $_GET['from'].'.php?post=1';
	  if (!empty($_GET['url'])) {
		  $from .= '&url='.$_GET['url'];
	  }
	  if (!empty($_GET['title'])) {
		  $from .= '&title='.$_GET['title'];
	  }

  }
  $smarty->assign('community', true);
  $smarty->assign('content_title', 'login');
  $smarty->assign('from', $from);
  $smarty->assign('content', 'login');
  $smarty->display('master_page.tpl');
?>
