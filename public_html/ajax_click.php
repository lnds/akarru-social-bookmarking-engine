<?
header("Content-Type: text/xml");
include_once('akarru.lib/common.php');
print '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes" ?>';
?>
<root>
<?php
$meme_id = intval($_GET['meme_id']);
if($meme_id > 0)
{
	$bm_memes = new memes($bm_db, $bm_user, $bm_promo_level);
	$bm_memes->click($meme_id, $bm_user);
	echo "<data>".$bm_memes->get_votes($meme_id)."</data>";
	echo "<meme_id>$meme_id</meme_id>";
}
?>
</root>

