<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  $smarty->assign('content_title', $content_title_meme_queue);
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  $data = $memes->get_new_memes($bm_page);

  $smarty->assign('memes', $data);
  $smarty->assign('bm_message', $bm_message_meme_queue);
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('content_feed_link', $bm_queue_feeds);
  $smarty->assign('show_ads', showGGAds());
  $smarty->assign('content', 'memes_grid');
  $smart_id = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
  $smarty->assign('pag_id', 'new');
  $smarty->display('master_page.tpl', $smart_id);
?>

