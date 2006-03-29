<?php
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', $bl_community);
  $smarty->assign('community', $bm_users->get_profile_links(0));
  $smarty->assign('content', 'community_grid');
  $smarty->assign('content_feed_link', $bm_main_feeds);
  $smarty->assign('show_ads', true);
  $smarty->display('master_page.tpl');
?>
