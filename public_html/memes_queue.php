<?php
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', 'votar pendientes');
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  $data = $memes->get_new_memes($bm_page);
  $smarty->assign('page_title', 'blogmemes - '.$data[0]->title);
  $smarty->assign('memes', $data);
  $smarty->assign('bm_message', '<em>Vota por estos memes para que aparezcan en la primera página</em>');
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('content_feed_link', $bm_queue_feeds);
  $smarty->assign('show_ads', true);
  $smarty->assign('content', 'memes_grid');
  $smarty->display('master_page.tpl');
?>

