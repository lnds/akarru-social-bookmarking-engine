<?php
  $meme_id = $_GET['meme_id'];
  if (empty($meme_id)) {
	  $meme_id = $_POST['meme_id'];
  }
  if (empty($meme_id)) {
	  header("Location: index.php");
	  exit();
	  return;
  }
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', 'comentarios');
  $memes = new memes($bm_db, $bm_user);
  if (!empty($_POST)) {
	  $memes->add_comment($meme_id, $_POST['comment']);
	  if (isset($_POST['position'])) {
		  $memes->debate($meme_id, $bm_user, $_POST['position'], false);

	  }
	  header("Location: comment.php?meme_id=$meme_id");
	  exit();
	  return;
  }
  $memes->debate($meme_id, $bm_user, 0, true);
  $meme = $memes->get_meme($meme_id);
  $comments = $memes->get_comments($meme_id);
  $smarty->assign('meme', $meme);
  $smarty->assign('meme_id', $meme_id);
  $smarty->assign('community', true);
  $smarty->assign('content', 'comment');
  $smarty->assign('comments', $comments);         
  $smarty->assign('page', $page);
  if ($meme->allows_debates) 
  {
	  $smarty->assign('friends', $memes->get_friends($meme_id));
	  $smarty->assign('foes', $memes->get_foes($meme_id));

	  $sponsors =  $memes->get_voters($meme_id);
	  $smarty->assign('sponsors', $sponsors);
	  $neutrals = $memes->get_neutrals($meme_id);
	  $neutrals = array_diff($neutrals, $sponsors);
	  $neutrals[] = '<img border="0" src="/anon40.png" alt="anonimo" /><br /><a href="register.php">'.$meme->clicks.'&nbsp;'.$bl_anonymous.'</a>'; 
	  $smarty->assign('neutrals', $neutrals);
  }
  else
  {
	  $smarty->assign('voters', $memes->get_voters($meme_id));
  }
  $smarty->display('master_page.tpl');
?>
