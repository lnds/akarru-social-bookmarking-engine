<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  
  $smarty->caching = 2; // lifetime is per cache
  // folksonomy_grid
  if (!$smarty->is_cached("folksonomy_grid.tpl", "db|tags|folksonomy|" . $bm_page))
  {
    $smarty->cache_lifetime = 3600*24; // Every 24 hours
    $smarty->assign('all_tags', $bm_tags->fetch_all($bm_page));
    $smarty->assign('pages', $bm_tags->count_pages()+1);
  }

  $folksonomy_grid = $smarty->fetch('folksonomy_grid.tpl','db|tags|folksonomy|' . $bm_page);
  $smarty->caching = false; // turn off caching

  $smarty->assign('content_title', $content_title_folksonomy);
  $smarty->assign('cached_content', TRUE);
  $smarty->assign('content', $folksonomy_grid);  
  $smarty->assign('show_ads', showGGAds());
  $smarty->display('master_page.tpl');
?>

