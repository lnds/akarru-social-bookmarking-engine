<?php
include_once('akarru.lib/common.php');

$comments_feed_cache = $rssCache . 'comments_feed.rss';
$comments_feed_tolerance = 60*30; // lifetime of the cache in seconds

if (isCacheUpdateNeeded($comments_feed_cache, $comments_feed_tolerance))
{
    $bm_memes = new memes($bm_db, $bm_user);
    $comments = $bm_memes->get_recent_comments();
    if (isset($comments[0]))
    {
        doConditionalGet($comments[0]->date_posted, "commentsRSSFeed");
    }
    
    startUpdate();    
    print '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
    ?>
    <rss version="2.0">
    <channel>
    <title><? echo($bm_site_name); ?> - <? echo($bl_comments); ?></title>
    <link><? echo($bm_url); ?></link>
    <language><? echo($bm_lang); ?></language>
    <description><? echo($bm_desc); ?></description>
    <copyright>(c) 2006 Kenji BAHEUX</copyright>
    <generator>akarru social bookmarking engine</generator>
    <ttl>5</ttl>
    <image>
    <title><? echo($bm_site_name); ?> - <? echo($bl_comments); ?></title>
    <link><? echo($bm_url); ?></link>
    <url><? echo($bm_url . "logo.gif"); ?></url>
    <width>103</width>
    <height>80</height>
    </image>
    <?php
    foreach ($comments as $comment)
    {
        print '<item>';
        print '<title>' . htmlspecialchars($comment->username . ': ' . $comment->title) . '</title>';
        print '<description>' . htmlspecialchars($comment->content) . '</description>';
        print '<pubDate>' . date("r", $comment->date_posted) . '</pubDate>';
        print '<link>' . $comment->permalink . '</link>';
        print '<guid isPermaLink="true">' . $comment->permalink . '</guid>';
        print '<author>dummy@acme.com (' . $comment->username . ')</author>';
        print '</item>';
    }
    ?>
    </channel>
    </rss>
    <?php
    endUpdate($comments_feed_cache);
}
header("Content-Type: text/xml");
printCache($comments_feed_cache);
?>