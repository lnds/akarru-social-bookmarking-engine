<?
  include_once('akarru.lib/common.php');
  $page = $_GET['page'];
  $smarty->assign('content_title', '&uacute;ltimos memes');
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);

  $smarty->assign('memes', $memes->get_memes($page, ''));
  if ($memes->pages > 50) {
	  $memes->pages = 50;
  }
  if ($bm_users->is_logged_in()) {
	  $smarty->assign('special_message', '<p><strong>¿Puedes escribir un relato con s&oacute;lo 100 palabras?</strong><br/><a href="100words.php" style="color:white;font-width:bold;font-size:12pt;">escribe un micro cuento</a></p>');
  }
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('$bm_message', 'estos memes han sido promovidos recientemente a la primera p&aacute;na');
  $smarty->assign('content', 'memes_grid');
  $smarty->assign('content_feed_link', $bm_main_feeds);
  $smarty->assign('show_ads', $page < $memes->pages);
  $smarty->display('master_page.tpl');
?>
