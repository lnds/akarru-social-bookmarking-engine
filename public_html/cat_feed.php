<?php
include_once('akarru.lib/common.php');
$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
if ($cat_id == 0) {
    logerror("cat_feed.php: cat_id = 0", "phpErrors");
	header('Location: /404.php');
	exit();
	return;
}

$cat_feed_cache = $rssCache . 'cat_' . $cat_id . '_feed.rss';
$cat_feed_tolerance = 60*30; // lifetime of the cache in seconds

if (isCacheUpdateNeeded($cat_feed_cache, $cat_feed_tolerance))
{
    $bm_memes = new memes($bm_db, $bm_user);
    $memes = $bm_memes->get_memes_by_category($cat_id);
    if (isset($memes[0]))
    {
        doConditionalGet($memes[0]->date_posted, "category" . $cat_id . "RSSFeed");
    }
    $cat_title = $bm_memes->get_category_name($cat_id);
    
    if (!$cat_title)
    {
        logerror("cat_feed.php: unknown cat_id = " . $cat_id, "phpErrors");
        header('Location: /404.php');
        exit();
        return;
    }
    
    startUpdate();
    print '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
    ?>
    <rss version="2.0">
    <channel>
    <title><? echo($bm_site_name); ?> - <? echo($bl_cat_meme . ': ' . $cat_title); ?></title>
    <link><? echo($bm_url); ?></link>
    <language><? echo($bm_lang); ?></language>
    <description><? echo($bm_desc); ?></description>
    <copyright>(c) 2006 Kenji BAHEUX</copyright>
    <generator>akarru social bookmarking engine</generator>
    <ttl>5</ttl>
    <image>
    <title><? echo($bm_site_name); ?> - <? echo($bl_cat_meme . ': ' . $cat_title); ?></title>
    <link><? echo($bm_url); ?></link>
    <url><? echo($bm_url . "logo.gif"); ?></url>
    <width>103</width>
    <height>80</height>
    </image>
    <?php
    foreach ($memes as $meme)
    {
        print '<item>';
        print '<title>' . htmlspecialchars($meme->title) . '</title>';
        print '<description>' . htmlspecialchars($meme->content) . '</description>';
        print '<pubDate>' . date("r", $meme->date_posted) . '</pubDate>';
        print '<link>' . $meme->permalink . '</link>';
        print '<guid isPermaLink="true">' . $meme->permalink . '</guid>';
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
    endUpdate($cat_feed_cache);
}
header("Content-Type: text/xml; charset=UTF-8");
printCache($cat_feed_cache);
?>
