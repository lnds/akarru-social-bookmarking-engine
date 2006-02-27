<?php
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', 'lo menos popular');
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  $smarty->assign('memes', $memes->get_memes($page, 'order by vote_count asc', 150));
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('show_ads', $page < $memes->pages);
  $smarty->assign('content', 'memes_grid');
  $smarty->display('master_page.tpl');
?>

