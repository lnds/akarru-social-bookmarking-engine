<?php
  include_once('akarru.lib/common.php');
  $page = $_GET['page'];
  $smarty->assign('content_title', 'votar pendientes');
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  $smarty->assign('memes', $memes->get_new_memes($page));
  $smarty->assign('bm_message', '<em>Vota por estos memes para que aparezcan en la primera p�gina</em>');
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('content_feed_link', $bm_queue_feeds);
  $smarty->assign('show_ads', true);
  $smarty->assign('content', 'memes_grid');
  $smarty->assign('show_ads', true);
  $smarty->display('master_page.tpl');
?>

