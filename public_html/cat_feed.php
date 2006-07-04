<?php
include_once('akarru.lib/common.php');
$cat_id = intval($_GET['cat_id']);
if ($cat_id == 0) {
    logerror("cat_feed.php: cat_id = 0", "phpErrors");
	header('Location: /404.php');
	exit();
	return;
}
header("Content-Type: text/xml");

$bm_memes = new memes($bm_db, $bm_user);
$memes = $bm_memes->get_memes_by_category($cat_id, $page, '', $page == 0? 200 : 0);
print '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
?>
<rss version="2.0">
<channel>
<title><? echo($bm_site_name); ?> - <? echo($bm_memes->get_category_name($cat_id)); ?></title>
<link><? echo($bm_url); ?></link>
<language><? echo($bm_lang); ?></language>
<description><? echo($bm_desc); ?></description>
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
	echo '<item>';
	echo '<title>'; echo htmlspecialchars($meme->title); echo '</title>';
	echo '<description>'; echo htmlspecialchars($meme->content); echo '</description>';
	echo '<pubDate>'; echo date("r", $meme->date_posted); echo '</pubDate>';
	echo '<link>' . $permalink . '</link>';
    echo '<guid isPermaLink="true">' . $permalink . '</guid>';
	echo '</item>';
}
?>
</channel>
</rss>
