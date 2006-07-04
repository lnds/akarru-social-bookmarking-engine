<?php
header("Content-Type: application/rss+xml");
include_once('akarru.lib/common.php');
$bm_memes = new memes($bm_db, $bm_user);
$memes = $bm_memes->get_new_memes($page, ' order by date_posted desc ', $page == 0? 200 : 0);
print '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
?>
<rss version="2.0">
<channel>
<title><? echo($bm_site_name); ?> <? echo($content_title_meme_queue); ?></title>
<link><? echo($bm_url); ?></link>
<description><? echo($bm_desc); ?></description>
<language><? echo($bm_lang); ?></language>
<copyright>(c) 2005 Eduardo Diaz Cortes</copyright>
<generator>akarru social bookmarking engine</generator>
<ttl>5</ttl>
<image>
<url>http://botones.blogalaxia.com/img/blogalaxia0.gif</url>
<title>Blogmemes in Blogalaxia</title>
<link>http://www.blogalaxia.com/top100.php?top=1</link>
</image>
<?php
foreach ($memes as $meme)
{
	$permalink = $bm_memes->get_permalink($meme->ID);
	print '<item>';
	print '<title>'.htmlspecialchars($meme->title).'</title>';
	print '<description>'.htmlspecialchars($meme->content).'</description>';
	print '<pubDate>'.date("r", $meme->date_posted).'</pubDate>';
	print '<link>' . $permalink . '</link>';
    print '<guid isPermaLink="true">' . $permalink . '</guid>';
    print '<author>dummy@acme.com (' . $meme->username . ')</author>';
    print '<category>' . $meme->cat_title . '</category>';
	print '</item>';
}
?>
</channel>
</rss>

