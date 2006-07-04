<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  $smarty->assign('content_title', $content_title_popular);
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  $data =   $memes->get_memes($bm_page, 'order by votes desc');
  $smarty->assign('memes', $data);

  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('show_ads', showGGAds() && ($page < $memes->pages));
  $smarty->assign('content', 'memes_grid');
  $smarty->display('master_page.tpl');
?>

