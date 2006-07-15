<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  $meme_id = intval($_GET['meme_id']);
  if ($meme_id == 0) {
	  $meme_id = intval($_POST['meme_id']);
  }
  if ($meme_id == 0) {
	  header("Location: /404.php");
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
  $smarty->display('master_page.tpl');
?>