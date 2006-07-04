<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  $smarty->assign('content_title', $content_title_tag_meme);
  $memes = new memes($bm_db, $bm_user);
  if (!empty($_POST)) {
	  $meme_id = (int) $_POST['meme_id'];
	  $tags = $_POST['tags'];
	  $memes->post_tags($meme_id, $bm_user, $tags);
  }
  else
  {
	  $meme_id = (int) $_GET['meme_id'];
  }
  $meme = $memes->get_meme($meme_id);
  $smarty->assign('meme', $meme);
  $smarty->assign('meme_id', $meme_id);
  $smarty->assign('sub_title', "[" . $content_title_tag_meme . "] " . $meme->title);
  $tags = $memes->get_tags($meme_id);
  $memes_tags = array();
  foreach ($tags as $tag)
  {
	  $memes_tags[] = '&nbsp;<a href="/memes_by_tag.php?tag_name='. $tag->tag.'">'.$tag->tag.'</a>&nbsp;&nbsp;';
  }
  $smarty->assign('meme_tags', $memes_tags);
  $smarty->assign('content', 'tag_meme');
  $smarty->display('master_page.tpl');
?>
