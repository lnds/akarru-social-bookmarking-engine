<?php
  include_once('akarru.lib/common.php');
  $page = $_GET['page'];
  $smarty->assign('content_title', 'lo m&aacute;s popular');
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  $smarty->assign('memes', $memes->get_memes($page, 'order by votes desc'));
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('show_ads', $page < $memes->pages);
  $smarty->assign('content', 'memes_grid');
  $smarty->display('master_page.tpl');
?>

