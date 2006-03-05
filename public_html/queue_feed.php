<?php
header("Content-Type: text/xml");
include_once('akarru.lib/common.php');
$bm_memes = new memes($bm_db, $bm_user);
$memes = $bm_memes->get_new_memes($page, ' order by date_posted desc ', $page == 0? 200 : 0);
print '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes" ?>';
?>
<rss version="2.0">
<channel>
<title><?= $bm_site_name ?> - Nuevos Memes</title>
<link><?= $bm_url ?></link>
<language><?= $bm_lang ?></language>
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
	echo '<item>';
	echo '<title>'; echo htmlspecialchars($meme->title); echo '</title>';
	echo '<description>'; echo htmlspecialchars($meme->content); echo '</description>';
	echo '<pubDate>'; echo date("r", $meme->date_posted); echo '</pubDate>';
	echo '<link>http://www.blogmemes.com/comment.php?meme_id='.$meme->ID.'</link>';
	echo '</item>';
}
?>
</channel>
</rss>

