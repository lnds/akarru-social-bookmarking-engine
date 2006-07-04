<?php
  include_once('akarru.lib/common.php');

  include_once('common_elements.php');
  
    $page_size = 120;
    $smarty->caching = 2; // lifetime is per cache
    // community_grid
    if (!$smarty->is_cached("community_grid.tpl", "db|users|community|" . $bm_page))
    {
        $smarty->cache_lifetime = 3600*24; // Every 24 hours
        $smarty->assign('community', $bm_users->get_profile_links(0, $bm_page,$page_size));
    }

    $community_grid = $smarty->fetch('community_grid.tpl','db|users|community|' . $bm_page);
    $smarty->caching = false; // turn off caching
  
  $smarty->assign('content_title', $bl_community);
  $pages = ceil($bm_users->count_users()/$page_size);
  $smarty->assign('pages', $pages+1);
  $smarty->assign('cached_content', TRUE);
  $smarty->assign('content', $community_grid);
  $smarty->assign('content_feed_link', $bm_main_feeds);
  $smarty->assign('show_ads', showGGAds());
  $smarty->display('master_page.tpl');
?>
