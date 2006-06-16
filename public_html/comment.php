<?php
  $meme_id = intval($_GET['meme_id']);
  if ($meme_id == 0) {
	  $meme_id = intval($_POST['meme_id']);
  }
  if ($meme_id == 0) {
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
	  header("Location: /meme/$meme_id");
	  exit();
	  return;
  }
  $memes->debate($meme_id, $bm_user, 0, true);
  $meme = $memes->get_meme($meme_id, intval($_GET['share']));
  $comments = $memes->get_comments($meme_id);
  $smarty->assign('page_title', 'blogmemes - '.$meme->title);
  $smarty->assign('meme', $meme);
  $smarty->assign('meme_id', $meme_id);
  $smarty->assign('community', true);
  $smarty->assign('content', 'comment');
  $smarty->assign('comments', $comments);         
  $smarty->assign('page', $page);

  $memes_tags = array();
  $tags = $memes->get_tags($meme_id,12);
  foreach ($tags as $tag)
  {
	  $memes_tags[] = '&nbsp;<a href="/tag/'.$tag->tag.'">'.$tag->tag.'</a>&nbsp;&nbsp;';
  }
  $smarty->assign('tags_of_meme', $memes_tags);
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
