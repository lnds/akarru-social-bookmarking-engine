<?php 
$memes = $bm_memes->get_memes_by_category($cat_id, $page);
include_once('akarru.gui/meme_grid.php'); 
?>
