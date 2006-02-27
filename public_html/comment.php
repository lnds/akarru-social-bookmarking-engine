<?php
  if (empty($meme_id)) {
	  header("Location: index.php");
	  exit();
	  return;
  }
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', 'comentarios');
  $memes = new memes($bm_db, $bm_user);
  if (!empty($_POST)) {
	  $memes->add_comment($meme_id, $comment);
  }
  $meme = $memes->get_meme($meme_id);
  $comments = $memes->get_comments($meme_id);
  $smarty->assign('meme', $meme);
  $smarty->assign('meme_id', $meme_id);
  $smarty->assign('content', 'comment');
  $smarty->assign('comments', $comments);         
  $smarty->assign('page', $page);
  $smarty->assign('voters', $memes->get_voters($meme_id));
  $smarty->display('master_page.tpl');
?>
