<?php

include_once('akarru.lib/common.php');
$meme_id = intval($_GET['meme_id']);
if($meme_id > 0)
{
	$bm_memes = new memes($bm_db, $bm_user, $bm_promo_level);
	$bm_memes->social_click($meme_id, $bm_user);
}

?>
