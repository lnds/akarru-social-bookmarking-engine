<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  if ($bm_users->logoff())
  {
	  header("Location: $bm_home");
	  exit();
	  return;
  }
  $smarty->assign('content_title', $content_title_logout);
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  $smarty->assign('memes', $memes->get_memes($page));
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('content', 'memes_grid');
  $smarty->display('master_page.tpl');
?>
