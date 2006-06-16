<?
header("Content-Type: text/xml");
include_once('akarru.lib/common.php');
print '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes" ?>';
?>
<root>
<?php
$meme_id = intval($_GET['meme_id']);
if ($meme_id > 0) {
	$bm_memes = new memes($bm_db, $bm_user, $bm_promo_level);
	$user_id = intval($_GET['user_id']);
	if ($user_id == 1) { $bm_memes->promote($meme_id); }
	if ($user_id == 0) {
			$bm_memes->vote_anon($meme_id);
	}
	else if(!$bm_memes->check_votes_user($meme_id, $user_id)){
		$bm_memes->vote($meme_id,$user_id);
	}else{
		echo "<error>$be_already_voted</error>";
	}
	echo "<data>".$bm_memes->get_votes($meme_id)."</data>";
	echo "<meme_id>".$meme_id."</meme_id>";
}
?>
</root>
