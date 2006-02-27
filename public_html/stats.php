<?
  include_once('akarru.lib/common.php');
  include_once('akarru.lib/statistics.php');

  function memes($pa, $pb)
  {
	  return $pb->memes - $pa->memes;
  }

  function votes($pa, $pb)
  {
	  return $pb->votes - $pa->votes;
  }

  function comments($pa, $pb)
  {
	  return $pb->comments - $pa->comments;
  }


  function infl($pa, $pb)
  {
	  return $pb->rank - $pa->rank;
  }


  function pop($pa, $pb)
  {
	  return $pb->popularity - $pa->popularity;
  }

  $smarty->assign('content_title', 'estad&iacute;sticas');
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);

  $stats = new statistics($bm_db);
  $smarty->assign('users', $stats->count_users());
  $smarty->assign('clicks', $stats->count_clicks());
  $smarty->assign('memes', $stats->count_memes());
  $smarty->assign('votes', $stats->count_votes());
  $posters = $stats->top_posters(200); 
  if (!empty($sort)) {
	  usort($posters, $sort);
  }
  $smarty->assign('posters', $posters);
  $smarty->assign('comments', $stats->count_comments());

  $smarty->assign('content', 'stats');
  $smarty->assign('content_feed_link', $bm_main_feeds);
  $smarty->display('master_page.tpl');
?>

