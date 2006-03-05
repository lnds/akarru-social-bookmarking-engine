<?php
header("Content-Type: text/xml");
include_once('akarru.lib/common.php');
$bm_memes = new memes($bm_db, $bm_user, $bm_promo_level);
$memes = $bm_memes->get_memes($page, ' order by date_posted desc ', $page == 0? 200 : 0);
print '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes" ?>';
?>
<rss version="2.0">
<channel>
<title><?= $bm_site_name ?></title>
<link><?= $bm_url ?></link>
<description><?= $bm_desc ?></description>
<language><?= $bm_lang ?></language>
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
	print '<item>';
	print '<title>'.htmlspecialchars($meme->title).'</title>';
	print '<description>'.htmlspecialchars($meme->content).'</description>';
	print '<pubDate>'.date("r", $meme->date_posted).'</pubDate>';
	print '<link>http://www.blogmemes.com/comment.php?meme_id='.$meme->ID.'</link>';
	print '</item>';
}
?>
</channel>
</rss>
