<?php
include_once('akarru.lib/common.php');
$user_name = isset($_GET['user_name']) ? $_GET['user_name'] : "";
  if (empty($user_name)) {
      logerror("user_feed.php: user_name is empty", "phpErrors");
      header("Location: /404.php");
	  exit();
	  return;
  }
  
$user_feed_cache = $rssCache . 'user_' . md5($user_name) . '_feed.rss';
$user_feed_tolerance = 60*60; // lifetime of the cache in seconds

if (isCacheUpdateNeeded($user_feed_cache, $user_feed_tolerance))
{
  $user_id = $bm_users->get_user_id_by_user_name($user_name);
  if ($user_id <= 0)
  {
      logerror("user_feed.php: user '". $username ."' does not exist", "phpErrors");
      header("Location: /404.php");
	  exit();
	  return;
  }

    $bm_memes = new memes($bm_db, $bm_user);
    $memes = $bm_memes->get_memes_by_user($user_id);
    if (isset($memes[0]))
    {
        doConditionalGet($memes[0]->date_posted, "user" . $user_id . "RSSFeed");
    }
    $user = $bm_users->get_user_by_id($user_id);

    startUpdate();
    print '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
    ?>
    <rss version="2.0">
    <channel>
    <title><? echo($bm_site_name); ?> - <? echo($bl_profile_memes_by . ': ' .$user->username); ?></title>
    <link><? echo($bm_url); ?></link>
    <language><? echo($bm_lang); ?></language>
    <description><? echo($bm_desc); ?></description>
    <copyright>(c) 2006 Kenji BAHEUX</copyright>
    <generator>akarru social bookmarking engine</generator>
    <ttl>5</ttl>
    <image>
    <title><? echo($bm_site_name); ?> - <? echo($bl_profile_memes_by . ': ' . $user->username); ?></title>
    <link><? echo($bm_url . "user/" . $user->username); ?></link>
    <url><? echo($user->gravatar); ?></url>
    <width>80</width>
    <height>80</height>
    </image>
    <?php
    foreach ($memes as $meme)
    {
        $permalink = $bm_memes->get_permalink($meme->ID);
        print '<item>';
        print '<title>' . htmlspecialchars($meme->title) . '</title>';
        print '<description>' . htmlspecialchars($meme->content) . '</description>';
        print '<pubDate>' . date("r", $meme->date_posted) . '</pubDate>';
        print '<link>' . $permalink . '</link>';
        print '<guid isPermaLink="true">' . $permalink . '</guid>';
        print '<author>dummy@acme.com (' . $meme->username . ')</author>';
        print '<category>' . $cat_title . '</category>';
        $tags = $bm_memes->get_tags($meme->ID,6);
        foreach ($tags as $tag)
        {
            print '<category domain="http://www.technorati.com/tag">' . $tag->tag . '</category>';
        }
        print '</item>';
    }
    ?>
    </channel>
    </rss>
    <?php
    endUpdate($user_feed_cache);
}
header("Content-Type: text/xml; charset=UTF-8");
printCache($user_feed_cache);
?>

