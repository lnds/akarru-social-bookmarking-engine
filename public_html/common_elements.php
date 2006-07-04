<?php
  include_once('akarru.lib/common.php');
  
    $smarty->caching = 2; // lifetime is per cache
    // categories
    if (!$smarty->is_cached("categories.tpl", "db|categories"))
    {
        $smarty->cache_lifetime = 3600*24; // Every 24 hours
        $bm_cats = new categories($bm_db);
	    $bm_cats = $bm_cats->fetch_all();
	    $smarty->assign('bm_cats', $bm_cats);
	    foreach ($bm_cats as $cat)
        {
            $cats[] = '<a href="/show_cat.php?cat_name='.$cat->cat_title.'" >'.$cat->cat_title.'</a>';
	    }
	    $smarty->assign('cats_array', $cats);
    }

    $categories = $smarty->fetch('categories.tpl','db|categories');
    $smarty->assign('categories', $categories);
    
    // community sample
    if (!$smarty->is_cached("community_random_profiles.tpl", 'db|users|sample'))
    {
        $smarty->cache_lifetime = 60*10; // Every 10 minutes
        $smarty->assign('community_sample', $bm_users->get_random_profile_links(9));
    }
    $community_random_profiles = $smarty->fetch('community_random_profiles.tpl', 'db|users|sample');
    $smarty->assign('community_random_profiles', $community_random_profiles);
    
    // tag sample
    if (!$smarty->is_cached("tag_sample.tpl", 'db|tags|sample'))
    {
        $smarty->cache_lifetime = 3600*12; // Every 12 hours
        $smarty->assign('tags', $bm_tags->fetch_top(21));
    }
    
    $tag_sample = $smarty->fetch('tag_sample.tpl', 'db|tags|sample');
    $smarty->assign('tag_sample', $tag_sample);

$smarty->caching = false; // lifetime is per cache
?>