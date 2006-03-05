<?php 
$memes = $bm_memes->get_meme($meme_id);
include_once('akarru.gui/meme_grid.php');
$comments = $bm_memes->get_comments($meme_id);
include_once('akarru.gui/comments_list_grid.php');
?>
