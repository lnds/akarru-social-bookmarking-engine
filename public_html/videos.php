<?
  include_once('akarru.lib/common.php');
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  $data = $memes->get_video_memes($bm_page, '');
  

  $smarty->assign('content_title', $bl_last_memes);
  $smarty->assign('memes', $data);

  $smarty->assign('page_title', 'blogmemes - '.$data[0]->title);
  if ($memes->pages > 50) 
	  $memes->pages = 50;
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('$bm_message', $bl_promoted_message);
  $smarty->assign('content', 'memes_grid');
  $smarty->assign('content_feed_link', $bm_main_feeds);
  $smarty->assign('show_ads', true);
  echo $smarty->fetch('master_page.tpl');
?>
