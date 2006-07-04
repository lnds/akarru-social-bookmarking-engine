<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  $memes = new memes($bm_db, $bm_user, 0);
  $cat_id = intval($_GET['cat_id']);

  if (!empty($_GET['cat_name']))
  {
	  $cat_name = $_GET['cat_name'];
	  $cat_id = $memes->get_category_id($cat_name);
  }
  
  if ($cat_id == 0)
  {
    logerror("show_cat.php: cat_id = 0 (category was not found).", "phpErrors");
    header("Location: /404.php");
    exit();
    return;
  }
  else
  {
    $cat = $memes->get_category($cat_id);
    $smarty->assign('content_title', $cat->cat_title);
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
  $smarty->assign('show_ads', showGGAds());
  $smarty->display('master_page.tpl');
?>
