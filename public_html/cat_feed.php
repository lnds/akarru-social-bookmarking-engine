<?php
include_once('akarru.lib/common.php');
if (empty($cat_id)) {
	header('Location: feed.php');
	exit();
	return;
}
header("Content-Type: text/xml");
$bm_memes = new memes($bm_db, $bm_user);
$memes = $bm_memes->get_memes_by_category($cat_id, $page, '', $page == 0? 200 : 0);
print '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes" ?>';
?>
<rss version="2.0">
<channel>
<title><?= $bm_site_name ?> - <?= $bm_memes->get_category_name($cat_id) ?></title>
<language><?= $bm_lang ?></language>
<link><?= $bm_url ?></link>
<description><?= $bm_desc ?></description>
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
