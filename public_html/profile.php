<?php
  include_once('akarru.lib/common.php');
  $user_name = $_GET['user_name'];
  if (empty($user_name)) {
	  header("Location: $bm_home");
	  exit();
	  return;
  }
  $memes = new memes($bm_db, $bm_user, 0, 15);
  $profile = $bm_users->get_user_profile($user_name, $memes);
  $smarty->assign('user_profile', $profile);
  $view = $_GET['view'];
  if ($view == "c") {
	  $data = $memes->get_commented_memes_by_user($bm_page, $profile->id);
	  $bl_profile = 'estos son los memes comentados por ';
	  $smarty->assign('query_ext', '&view=c&user_name='.$user_name);
  }
  else if ($view == 'v') {
	  $data = $memes->get_voted_memes_by_user($bm_page, $profile->id);
	  $bl_profile = 'estos son los memes votados por ';
	  $smarty->assign('query_ext', '&view=v&user_name='.$user_name);
  }
  else {
	  $data = $memes->get_memes_by_user($profile->id, $bm_page);
	  $bl_profile = 'estos son los memes enviados por ';
	  $smarty->assign('query_ext', '&user_name='.$user_name);

  }
  $smarty->assign('memes', $data);
  $smarty->assign('page_title', 'blogmemes - '.$user_name);


  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('content_title', $bl_profile.' '.$profile->username);
  $smarty->assign('content', 'profile');
  $smarty->assign('page', $page);
  $smarty->display('master_page.tpl');
?>
