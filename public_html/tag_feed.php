<?php
include_once('akarru.lib/common.php');
$tag_id = isset($_GET['tag_id']) ? intval($_GET['tag_id']) : 0;
if ($tag_id == 0) {
    logerror("tag_feed.php: tag_id = 0", "phpErrors");
	header('Location: /404.php');
	exit();
	return;
}

$tag_feed_cache = $rssCache . 'tag_' . $tag_id . '_feed.rss';
$tag_feed_tolerance = 60*30; // lifetime of the cache in seconds

if (isCacheUpdateNeeded($tag_feed_cache, $tag_feed_tolerance))
{
    $bm_memes = new memes($bm_db, $bm_user);
    $memes = $bm_memes->get_memes_by_tag($tag_id);
    if (isset($memes[0]))
    {
        doConditionalGet($memes[0]->date_posted, "tag" . $tag_id . "RSSFeed");
    }

    $tag_name = $bm_memes->get_tag_name($tag_id);
    
    if (!$tag_name)
    {
        logerror("tag_feed.php: unknown tag_id = " . $tag_id, "phpErrors");
        header('Location: /404.php');
        exit();
        return;
    }
    
    startUpdate();    
    print '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
    ?>
    <rss version="2.0">
    <channel>
    <title><? echo($bm_site_name); ?> - <? echo($bl_tag_meme .': '. $tag_name); ?></title>
    <link><? echo($bm_url); ?></link>
    <language><? echo($bm_lang); ?></language>
    <description><? echo($bm_desc); ?></description>
    <copyright>(c) 2006 Kenji BAHEUX</copyright>
    <generator>akarru social bookmarking engine</generator>
    <ttl>5</ttl>
    <image>
    <title><? echo($bm_site_name); ?> - <? echo($bl_tag_meme . ': ' . $tag_name); ?></title>
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
        print '<category>' . $meme->category . '</category>';
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
    endUpdate($tag_feed_cache);
}
header("Content-Type: text/xml");
printCache($tag_feed_cache);
?>