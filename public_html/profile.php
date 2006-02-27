<?php
  include_once('akarru.lib/common.php');
  if (empty($user_name)) {
	  header("Location: $bm_home");
	  exit();
	  return;
  }
  $memes = new memes($bm_db, $bm_user, 0, 5);
  $profile = $bm_users->get_user_profile($user_name, $memes);
  $smarty->assign('user_profile', $profile);
  $smarty->assign('memes', $memes->get_memes_by_user($profile->id));
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('content_title', $bl_profile.' '.$profile->username);
  $smarty->assign('content', 'profile');
  $smarty->display('master_page.tpl');
?>
