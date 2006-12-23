<?php
  include_once('akarru.lib/common.php');
  if ($bm_users->logoff())
  {
	  header("Location: $bm_home");
	  exit();
	  return;
  }
  $smarty->assign('content_title', '&uacute;ltimos memes');
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  $smarty->assign('memes', $memes->get_memes($page));
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('content', 'memes_grid');
  $smarty->display('master');
?>
