<?
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', '&uacute;ltimos memes');
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);

  $smarty->assign('memes', $memes->get_memes($page, '', $memes->records_to_page*50 ));
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('$bm_message', 'estos memes han sido promovidos recientemente a la primera p&aacute;na');
  $smarty->assign('content', 'memes_grid');
  $smarty->assign('content_feed_link', $bm_main_feeds);
  $smarty->assign('show_ads', $page < $memes->pages);
  $smarty->display('master_page.tpl');
?>
