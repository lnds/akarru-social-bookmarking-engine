<?php
  include_once('akarru.lib/common.php');

  $meme_id = $_GET['meme_id'];
  if (empty($meme_id)) {
	  $meme_id = $_POST['meme_id'];
  }
  if (empty($meme_id)) {
	  header("Location: index.php");
	  exit();
	  return;
  }
    
  $memes = new memes($bm_db, $bm_user);
  $meme = $memes->get_meme($meme_id);
  $smarty->assign('meme', $meme);
  $smarty->assign('meme_id', $meme_id);
  $smarty->assign('permalink', $memes->get_permalink($meme_id));
  $smarty->assign('content', 'sendlink_ok');
  $smarty->assign('content_title', $content_title_sendlink);
  $smarty->assign('community', true);
  $smarty->assign('page', $page);
  $smarty->display('master_page.tpl');
?>