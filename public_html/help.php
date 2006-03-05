<?php
  $page = $_GET['page'];
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', 'preguntas frecuentes');
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  $smarty->assign('memes', $memes->get_memes($page));
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('$bm_message', 'estos memes han sido promovidos recientemente a la primera p&aacute;na');
  $smarty->assign('content', 'help');
  $smarty->display('master_page.tpl');
?>

