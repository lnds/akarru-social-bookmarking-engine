<?php
  include_once('akarru.lib/common.php');
  $smarty->assign('page_title', 'blogmemes - comunidad');
  $smarty->assign('content_title', $bl_community);
  $page_size = 120;
  $pages = ceil($bm_users->count_users()/$page_size);
  $smarty->assign('pages', $pages+1);
  $smarty->assign('community', $bm_users->get_profile_links(0, $bm_page,$page_size));
  $smarty->assign('content', 'community_grid');
  $smarty->assign('content_feed_link', $bm_main_feeds);
  $smarty->assign('show_ads', true);
  $smarty->display('master_page.tpl');
?>
