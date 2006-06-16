<?php
  include_once('akarru.lib/common.php');
  $tag_id = intval($_GET['tag_id']);
  $bm_no_folkbar = true;
  $memes = new memes($bm_db, $bm_user);
  if ($tag_id > 0) {
	  $bm_title = 'Etiqueta: '.$memes->get_tag_name($tag_id);
  }
  else
  {
	  $tag_name = $_GET['tag_name'];
	  $bm_title = 'Etiqueta: '.$tag_name;
	  $tag_id = $memes->get_tag_id($tag_name);
  }
  $smarty->assign('content_title', $bm_title);
  $data = $memes->get_memes_by_tag($tag_id,  $bm_page, 'order by date_promo desc, votes desc');
  $smarty->assign('page_title', 'blogmemes - '.$data[0]->title);
  $smarty->assign('memes', $data);
  $smarty->assign('pages',$memes->pages+1);
  $smarty->assign('content', 'memes_grid');
  $smarty->display('master_page.tpl');
?>
