<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  $tag_id = isset($_GET['tag_id']) ? intval($_GET['tag_id']) : 0;
  $bm_no_folkbar = true;
  $memes = new memes($bm_db, $bm_user);
  if (! empty($_GET['tag_name']))
  {
	  $tag_name = $_GET['tag_name'];
	  $bm_title = $bl_tag_meme .': '.$tag_name;
	  $tag_id = $memes->get_tag_id($tag_name);
  }

  if ($tag_id == 0)
  {
    logerror("memes_by_tag.php: tag_id = 0 (tag '". $_GET['tag_name'] . "' was not found).", "phpErrors");
    header("Location: /404.php");
    exit();
    return;
  }
  else
  {
    $bm_title = $bl_tag_meme .': '.$memes->get_tag_name($tag_id);
  }

  $feed_url = "tag_feed.php?tag_id=$tag_id";
  $smarty->assign('content_feed', $feed_url);
  $smarty->assign('content_title', $bm_title);
  $data = $memes->get_memes_by_tag($tag_id,  $bm_page, 'order by date_promo desc, votes desc');
  $smarty->assign('memes', $data);
  $smarty->assign('pages',$memes->pages+1);
  $smarty->assign('content_feed_link', "<a href=\"$feed_url\">".'<img src="http://www.feedburner.com/fb/images/pub/feed-icon16x16.png" border="0"/></a>');
  $smarty->assign('content', 'memes_grid');
  $smarty->assign('show_ads', showGGAds());
  $smarty->display('master_page.tpl');
?>
