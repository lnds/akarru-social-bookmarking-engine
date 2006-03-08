<?php
  include_once('akarru.lib/common.php');
  $user_name = $_GET['user_name'];
  if (empty($user_name)) {
	  header("Location: $bm_home");
	  exit();
	  return;
  }
  $page = $_GET['page'];
  if (empty($page)) {
	  $page = 0;
  }
  $memes = new memes($bm_db, $bm_user, 0, 5);
  $profile = $bm_users->get_user_profile($user_name, $memes);
  $smarty->assign('user_profile', $profile);
  $smarty->assign('memes', $memes->get_memes_by_user($profile->id, $page));


  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('content_title', $bl_profile.' '.$profile->username);
  $smarty->assign('content', 'profile');
  $smarty->assign('page', $page);
  $smarty->assign('query_ext', '&user_name='.$user_name);
  $smarty->display('master_page.tpl');
?>
