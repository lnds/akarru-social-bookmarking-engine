<?php
include_once('akarru.lib/common.php');

$meme_id = 0;
if (isset($_GET['meme_id']))
{
    $meme_id = intval($_GET['meme_id']);
    $comments_feed_cache = $rssCache . 'comments_' . $meme_id . '_feed.rss';
}
else
{
    $comments_feed_cache = $rssCache . 'comments_feed.rss';
}
$comments_feed_tolerance = 60*30; // lifetime of the cache in seconds
    

if (isCacheUpdateNeeded($comments_feed_cache, $comments_feed_tolerance))
{
    $bm_memes = new memes($bm_db, $bm_user);
    if ($meme_id > 0)
    {
        $comments = $bm_memes->get_comments($meme_id);
        $meme = $bm_memes->get_meme($meme_id);
        $feed_title = $bm_site_name. ' - ' . $meme->title . ": " . $bl_comments;
        
    }
    else
    {
        $comments = $bm_memes->get_recent_comments();
        $feed_title = $bm_site_name. ' - ' . $bl_comments;
    }
    if (isset($comments[0]))
    {
        doConditionalGet($comments[0]->date_posted, 'commentsRSS' . $meme_id . 'Feed');
    }
    
    startUpdate();    
    print '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
    ?>
    <rss version="2.0">
    <channel>
    <title><? echo($feed_title); ?></title>
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
    if ($meme_id > 0)
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