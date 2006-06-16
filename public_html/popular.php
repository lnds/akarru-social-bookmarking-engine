<?php
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', 'lo m&aacute;s popular');
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  $data =   $memes->get_memes($bm_page, 'order by votes desc');
  $smarty->assign('memes', $data);
  $smarty->assign('page_title', 'blogmemes - '.$data[0]->title);
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('show_ads', $page < $memes->pages);
  $smarty->assign('content', 'memes_grid');
  $smarty->display('master_page.tpl');
?>

