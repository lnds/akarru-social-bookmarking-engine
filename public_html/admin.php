<?
  include_once('akarru.lib/common.php');
  include_once('akarru.lib/statistics.php');
  if ($bm_user != 1) {
      logerror("admin.php: bm_user not admin", "phpErrors");
	  header("Location: /403.php");
	  exit();
	  return;
  }
  
  include_once('common_elements.php');

  $smarty->assign('content_title', $content_title_admin);
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);

  $smarty->assign('memes', $memes->get_memes($page));
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $stats = new statistics($bm_db);
  $smarty->assign('users', $stats->count_users());
  $smarty->assign('clicks', $stats->count_clicks());
  $smarty->assign('memes', $stats->count_memes());
  $smarty->assign('votes', $stats->count_votes());
  $smarty->assign('posters', $stats->top_posters());
  $smarty->assign('comments', $stats->count_comments());

  $smarty->assign('content', 'admin');
  $smarty->assign('content_feed_link', $bm_main_feeds);
  $smarty->display('master_page.tpl');
?>
