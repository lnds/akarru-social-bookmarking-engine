<?php
  include_once('akarru.lib/common.php');
  $memes = new memes($bm_db, $bm_user, 0);
  $cat_id = intval($_GET['cat_id']);
  if ($cat_id > 0) 
  {
	  $cat = $memes->get_category($cat_id);
	  $smarty->assign('content_title', $cat->cat_title);
  }
  else
  {
	  $cat_name = $_GET['cat_name'];
	  $cat_id = $memes->get_category_id($cat_name);
	  $smarty->assign('content_title', $cat_name);
	  $cat = $memes->get_category($cat_id);
  }
  $feed_url = "cat_feed.php?cat_id=$cat_id";
  $smarty->assign('content_feed', $feed_url);
  $smarty->assign('query_ext', '&cat_id='.$cat_id);
  $smarty->assign('memes', $memes->get_memes_by_category($cat_id, $bm_page));
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  if (empty($cat->feed)) 
	  $smarty->assign('content_feed_link', "<a href=\"$feed_url\">".'<img src="http://www.feedburner.com/fb/images/pub/feed-icon16x16.png" border="0"/></a>');
  else
	  $smarty->assign('content_feed_link', $cat->feed);
  $smarty->assign('content', 'memes_grid');
  $smarty->display('master_page.tpl');
?>
