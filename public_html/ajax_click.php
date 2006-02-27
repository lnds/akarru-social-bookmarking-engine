<?
header("Content-Type: text/xml");
include_once('akarru.lib/common.php');
print '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes" ?>';
?>
<root>
<?php
if(isset($_GET['meme_id'])){
	$bm_memes = new memes($bm_db, $bm_user, $bm_promo_level);
	$bm_memes->click($_GET['meme_id']);
	echo "<data>".$bm_memes->get_votes($_GET['meme_id'])."</data>";
	echo "<meme_id>".$_GET['meme_id']."</meme_id>";
}
?>
</root>

