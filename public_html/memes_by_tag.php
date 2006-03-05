<?php
  include_once('akarru.lib/common.php');
  $bm_content = 'akarru.gui/tag_grid.php';
  $tag_id = $_GET['tag_id'];
  $page   = $_GET['page'];
  $bm_no_folkbar = true;
  $memes = new memes($bm_db, $bm_user, 0);
  $bm_title = 'Etiqueta: '.$memes->get_tag_name($tag_id);
  $smarty->assign('content_title', $bm_title);
  $smarty->assign('memes', $memes->get_memes_by_tag($tag_id,  $page, 'order by date_promo desc, vote_count desc'));
  $smarty->assign('content', 'memes_grid');
  $smarty->display('master_page.tpl');
?>
