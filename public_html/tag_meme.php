<?php
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', 'comentarios');
  $memes = new memes($bm_db, $bm_user);
  if (!empty($_POST)) {
	  $memes->post_tags($meme_id, $bm_user, $tags);
  }
  $meme = $memes->get_meme($meme_id);
  $smarty->assign('meme', $meme);
  $smarty->assign('meme_id', $meme_id);
  $smarty->assign('meme_tags', $memes->get_tags($meme_id));
  $smarty->assign('content', 'tag_meme');
  $smarty->display('master_page.tpl');
?>
