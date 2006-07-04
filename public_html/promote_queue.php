<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  if ($bm_users->get_is_admin() || $bm_user == 1) {
	  // if user is admin do promotion
	  $memes->promote_all();
  }
  $smarty->assign('content_title', $content_title_promote);

  $smarty->assign('memes', $memes->get_memes($bm_page));
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('$bm_message', 'estos memes han sido promovidos recientemente a la primera p&aacute;na');
  $smarty->assign('content', 'memes_grid');
  $smarty->assign('content_feed_link', $bm_main_feeds);
  $smarty->assign('show_ads', showGGAds());
  $smarty->display('master_page.tpl');
?>
