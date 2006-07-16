<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  
  $user_name = $_GET['user_name'];
  if (empty($user_name)) {
      logerror("profile.php: user_name is empty", "phpErrors");
      header("Location: /404.php");
	  exit();
	  return;
  }
  
  if ($bm_users->check_user_exists($user_name) == 0)
  {
      logerror("profile.php: user '". $username ."' does not exist", "phpErrors");
      header("Location: /404.php");
	  exit();
	  return;
  }
  
  $salt = "";
  if (strcmp($bm_users->get_user_name(), $user_name) == 0)
  {
    // We need to make a separate cache for the owner
    $salt = "|self";
  }
  
  if ($bm_users->get_is_admin())
  {
    // and for the administrators
    $salt.="|adm";
  }


  $smarty->caching = 2; // lifetime is per cache 

  $view = isset($_GET['view']) ? $_GET['view'] : "";
  if ($view == "c") {
      // profile
      if (!$smarty->is_cached("profile.tpl", "db|users|profile|" . $user_name . $salt . "|comments|" . $bm_page))
      {
        $smarty->cache_lifetime = 3600*24; // Every 24 hours
        $memes = new memes($bm_db, $bm_user, 0, 5);
        $profile = $bm_users->get_user_profile($user_name, $memes);
        $smarty->assign('user_profile', $profile);
        $data = $memes->get_commented_memes_by_user($bm_page, $profile->id);
        if ($memes->pages > 1) 
	        $smarty->assign('pages', $memes->pages+1);
        $smarty->assign('memes', $data);
	    $smarty->assign('query_ext', '&view=c&user_name='.$user_name);
      }

      $profile = $smarty->fetch('profile.tpl','db|users|profile|' . $user_name . $salt . '|comments|' . $bm_page);
      $profile_title = $bl_profile_comments_by;
  }
  else if ($view == 'v') {
      // profile
      if (!$smarty->is_cached("profile.tpl", "db|users|profile|" . $user_name . $salt . "|votes|" . $bm_page))
      {
        $smarty->cache_lifetime = 3600*24; // Every 24 hours
        $memes = new memes($bm_db, $bm_user, 0, 5);
        $profile = $bm_users->get_user_profile($user_name, $memes);
        $smarty->assign('user_profile', $profile);
        $data = $memes->get_voted_memes_by_user($bm_page, $profile->id);
        if ($memes->pages > 1) 
	        $smarty->assign('pages', $memes->pages+1);
        $smarty->assign('query_ext', '&view=v&user_name='.$user_name);
        $smarty->assign('memes', $data);
      }

      $profile = $smarty->fetch('profile.tpl','db|users|profile|' . $user_name . $salt . '|votes|' . $bm_page);
      $profile_title  = $bl_profile_votes_by;
  }
  else {
      // profile
      if (!$smarty->is_cached("profile.tpl", "db|users|profile|" . $user_name . $salt . "|posted-memes|" . $bm_page))
      {
        $smarty->cache_lifetime = 3600*24; // Every 24 hours
        $memes = new memes($bm_db, $bm_user, 0, 5);
        $profile = $bm_users->get_user_profile($user_name, $memes);
        $smarty->assign('user_profile', $profile);
        $data = $memes->get_memes_by_user($profile->id, $bm_page);
        if ($memes->pages > 1)
        	$smarty->assign('pages', $memes->pages+1);

	    $smarty->assign('query_ext', '&user_name='.$user_name);
        $smarty->assign('memes', $data);
      }

      $profile = $smarty->fetch('profile.tpl','db|users|profile|' . $user_name . $salt . '|posted-memes|' . $bm_page);
      $profile_title = $bl_profile_memes_by;      
  }

  $smarty->caching = false; // turn off caching
  
  $feed_url = "/user/feed/" . $user_name;
  $smarty->assign('content_feed', $feed_url);
  $smarty->assign('content_feed_link', "<a href=\"$feed_url\">".'<img src="http://www.feedburner.com/fb/images/pub/feed-icon16x16.png" border="0"/></a>');
  $smarty->assign('content_title', $bl_profile.' '.$profile->username);
  $smarty->assign('profile_title', $profile_title);
  $smarty->assign('cached_content', TRUE);
  $smarty->assign('content', $profile);
  $smarty->assign('show_ads', showGGAds());
  $smarty->display('master_page.tpl');
?>