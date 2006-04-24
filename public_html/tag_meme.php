<?php
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', 'comentarios');
  $memes = new memes($bm_db, $bm_user);
  if (!empty($_POST)) {
	  $meme_id = $_POST['meme_id'];
	  $tags = $_POST['tags'];
	  $memes->post_tags($meme_id, $bm_user, $tags);
  }
  else
  {
	  $meme_id = $_GET['meme_id'];
  }
  $meme = $memes->get_meme($meme_id);
  $smarty->assign('meme', $meme);
  $smarty->assign('meme_id', $meme_id);
  $tags = $memes->get_tags($meme_id);
  $memes_tags = array();
  foreach ($tags as $tag)
  {
	  $memes_tags[] = '&nbsp;<a href="memes_by_tag.php?tag_id='.$tag->ID.'">'.$tag->tag.'</a>&nbsp;&nbsp;';
  }
  $smarty->assign('meme_tags', $memes_tags);
  $smarty->assign('content', 'tag_meme');
  $smarty->display('master_page.tpl');
?>
