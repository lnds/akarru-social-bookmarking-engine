<?php
include_once('akarru.lib/common.php');

$feed_cache = $rssCache . 'feed.rss';
$feed_tolerance = 60*15; // lifetime of the cache in seconds

if (isCacheUpdateNeeded($feed_cache, $feed_tolerance))
{
    
    $bm_memes = new memes($bm_db, $bm_user);
    $memes = $bm_memes->get_memes(0, ' order by date_posted desc ', 1);
    if (isset($memes[0]))
    {
        doConditionalGet($memes[0]->date_posted, "mainRSSFeed");
    }
    startUpdate();
    print '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
    ?>
    <rss version="2.0">
    <channel>
    <title><? echo($bm_site_name); ?>  <? echo($bl_last_memes); ?></title>
    <link><? echo($bm_url); ?></link>
    <description><? echo($bm_desc); ?></description>
    <language><? echo($bm_lang); ?></language>
    <copyright>(c) 2006 Kenji BAHEUX</copyright>
    <generator>akarru social bookmarking engine</generator>
    <ttl>5</ttl>
    <image>
    <title><? echo($bm_site_name); ?>  <? echo($bl_last_memes); ?></title>
    <link><? echo($bm_url); ?></link>
    <url><? echo($bm_url . "logo.gif"); ?></url>
    <width>103</width>
    <height>80</height>
    </image>
    <?php
    foreach ($memes as $meme)
    {
        print '<item>';
        print '<title>'.htmlspecialchars($meme->title).'</title>';
        print '<description>'.htmlspecialchars($meme->content).'</description>';
        print '<pubDate>'.date("r", $meme->date_posted).'</pubDate>';
        print '<link>' . $meme->permalink . '</link>';
        print '<guid isPermaLink="true">' . $meme->permalink . '</guid>';
        print '<author>dummy@acme.com (' . $meme->username . ')</author>';
        print '<category>' . $meme->cat_title . '</category>';
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
    endUpdate($feed_cache);
}
header("Content-Type: text/xml; charset=UTF-8");
printCache($feed_cache);
?>