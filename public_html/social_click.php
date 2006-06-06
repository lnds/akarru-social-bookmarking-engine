<?php

include_once('akarru.lib/common.php');

if(isset($_GET['meme_id']))
{
    $meme_id = (int) $_GET['meme_id'];
	$bm_memes = new memes($bm_db, $bm_user, $bm_promo_level);
	$bm_memes->social_click($meme_id, $bm_user);
}

?>

