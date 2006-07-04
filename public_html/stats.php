<?
  include_once('akarru.lib/common.php');
  include_once('akarru.lib/statistics.php');
  include_once('common_elements.php');

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
	  if ($pb->influence == $pa->influence)
		  return 0;
	  return $pb->influence > $pa->influence ? 1 : -1;
  }


  function pop($pa, $pb)
  {
	  if ($pb->popularity == $pa->popularity)
		  return 0;
	  return $pb->popularity > $pa->popularity ? 1 : -1;
  }

  $smarty->assign('content_title', $content_title_stats);
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);

  $stats = new statistics($bm_db);
  $smarty->assign('users', $stats->count_users());
  $smarty->assign('clicks', $stats->count_clicks());
  $smarty->assign('memes', $stats->count_memes());
  $smarty->assign('votes', $stats->count_votes());
  $posters = $stats->top_posters(50); 

  $sort=$_GET['sort'];
  if (!empty($sort)) {
	  usort($posters, $sort);
  }
  $smarty->assign('posters', $posters);
  $smarty->assign('comments', $stats->count_comments());

  $smarty->assign('content', 'stats');
  $smarty->assign('content_feed_link', $bm_main_feeds);
  $smarty->display('master_page.tpl');
?>

