<?php 
$memes = $bm_memes->get_new_memes($page);
$bm_message = 'Promueve estos memes a la primera p&aacute;gina votando por ellos';
include_once('akarru.gui/meme_grid.php'); 
?>

