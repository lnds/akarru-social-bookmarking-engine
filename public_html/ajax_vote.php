<?
header("Content-Type: text/xml");
include_once('akarru.lib/common.php');
print '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes" ?>';
?>
<root>
<?php
if(isset($_GET['meme_id'])){
	$bm_memes = new memes($bm_db, $bm_user, $bm_promo_level);
	if ($_GET['user_id'] == 1) { $bm_memes->promote($_GET['meme_id' ]); }

	if (empty($_GET['user_id']) || $_GET['user_id'] == 0) {
			echo '<error>ya no se permiten votos anonimos. Registrate para votar.</error>';
	}
	else if(!$bm_memes->check_votes_user($_GET['meme_id'],$_GET['user_id'])){
		$bm_memes->vote($_GET['meme_id'],$_GET['user_id']);
	}else{
		echo "<error>$be_already_voted</error>";
	}
	echo "<data>".$bm_memes->get_votes($_GET['meme_id'])."</data>";
	echo "<meme_id>".$_GET['meme_id']."</meme_id>";
}
?>
</root>
